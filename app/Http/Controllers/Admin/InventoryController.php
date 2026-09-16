<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_products', ['only' => ['index', 'show']]);
        $this->middleware('permission:edit_products', ['only' => ['edit', 'update', 'adjustStock', 'addStock', 'removeStock']]);
    }

    /**
     * Display inventory listing with search and filters
     * Now queries from products table instead of separate inventory table
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by product name or SKU
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by stock status
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            $lowStockThreshold = 10; // Default reorder level
            
            switch ($status) {
                case 'out_of_stock':
                    $query->where('stock_quantity', 0);
                    break;
                case 'low_stock':
                    $query->whereBetween('stock_quantity', [1, $lowStockThreshold]);
                    break;
                case 'medium_stock':
                    $query->whereBetween('stock_quantity', [$lowStockThreshold + 1, $lowStockThreshold * 2]);
                    break;
                case 'high_stock':
                    $query->where('stock_quantity', '>', $lowStockThreshold * 2);
                    break;
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0);
                    break;
            }
        }

        $inventory = $query->paginate(20)->appends($request->query());
        
        // Get statistics from products table
        $lowStockThreshold = 10;
        $inStockCount = Product::where('stock_quantity', '>', 0)->count();
        $lowStockCount = Product::whereBetween('stock_quantity', [1, $lowStockThreshold])->count();
        $outOfStockCount = Product::where('stock_quantity', 0)->count();
        $totalValue = Product::selectRaw('SUM(stock_quantity * COALESCE(price, 0)) as total')
                        ->value('total') ?? 0;

        return view('admin.inventory.index', compact('inventory', 'inStockCount', 'lowStockCount', 'outOfStockCount', 'totalValue'));
    }

    /**
     * Display inventory item details
     */
    public function show(Inventory $inventory)
    {
        $inventory->load('product');
        
        return view('admin.inventory.show', compact('inventory'));
    }

    /**
     * Show edit form
     */
    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update inventory details
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'reserved_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        $inventory->update($validated);

        return redirect()->route('admin.inventory.show', $inventory)
            ->with('success', 'Inventory updated successfully!');
    }

    /**
     * Add stock to inventory
     */
    public function addStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        $inventory->addStock($validated['quantity']);
        
        if ($validated['cost_per_unit'] ?? null) {
            $inventory->update(['cost_per_unit' => $validated['cost_per_unit']]);
        }

        return redirect()->back()
            ->with('success', "Added {$validated['quantity']} units to stock!");
    }

    /**
     * Remove stock from inventory
     */
    public function removeStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($inventory->removeStock($validated['quantity'])) {
            return redirect()->back()
                ->with('success', "Removed {$validated['quantity']} units from stock!");
        }

        return redirect()->back()
            ->with('error', 'Insufficient stock to remove!');
    }

    /**
     * Adjust stock (legacy method for compatibility)
     */
    public function adjustStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'adjustment_quantity' => 'required|integer',
            'adjustment_reason' => 'required|string',
        ]);

        $inventory->quantity += $validated['adjustment_quantity'];
        $inventory->save();

        return redirect()->back()
            ->with('success', 'Stock adjusted successfully!');
    }
}
