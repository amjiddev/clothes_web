<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
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
     */
    public function index(Request $request)
    {
        $query = Inventory::with('product');

        // Search by product name or SKU
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('sku', 'like', "%{$search}%");
        }

        // Filter by stock status
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            switch ($status) {
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
                case 'low_stock':
                    $query->whereRaw('quantity <= reorder_level')
                          ->where('quantity', '>', 0);
                    break;
                case 'medium_stock':
                    $query->whereRaw('quantity > reorder_level')
                          ->whereRaw('quantity <= reorder_level * 2');
                    break;
                case 'high_stock':
                    $query->whereRaw('quantity > reorder_level * 2');
                    break;
            }
        }

        $inventory = $query->paginate(20)->appends($request->query());
        
        // Get statistics
        $lowStockCount = Inventory::whereRaw('quantity <= reorder_level')->count();
        $outOfStockCount = Inventory::where('quantity', 0)->count();
        $totalValue = Inventory::selectRaw('SUM(quantity * COALESCE(cost_per_unit, 0)) as total')
                        ->value('total') ?? 0;

        return view('admin.inventory.index', compact('inventory', 'lowStockCount', 'outOfStockCount', 'totalValue'));
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
