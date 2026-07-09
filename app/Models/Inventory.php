<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'quantity',
        'reserved_quantity',
        'reorder_level',
        'sku',
        'last_restock_date',
        'cost_per_unit',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'reorder_level' => 'integer',
        'cost_per_unit' => 'decimal:2',
        'last_restock_date' => 'datetime',
    ];

    /**
     * Relationship: Inventory belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get available quantity (quantity - reserved)
     */
    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->reorder_level;
    }

    /**
     * Check if can fulfill order quantity
     */
    public function canFulfill($quantity = 1)
    {
        return $this->available_quantity >= $quantity;
    }

    /**
     * Get stock percentage based on reorder level
     */
    public function getStockPercentageAttribute()
    {
        if ($this->reorder_level === 0) {
            return 100;
        }
        return min(($this->quantity / ($this->reorder_level * 2)) * 100, 100);
    }

    /**
     * Get stock status badge
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity === 0) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } elseif ($this->quantity > ($this->reorder_level * 2)) {
            return 'high_stock';
        }
        return 'medium_stock';
    }

    /**
     * Get inventory value (quantity * cost)
     */
    public function getInventoryValueAttribute()
    {
        return $this->quantity * ($this->cost_per_unit ?? 0);
    }

    /**
     * Add stock
     */
    public function addStock($quantity)
    {
        $this->quantity += $quantity;
        $this->last_restock_date = now();
        $this->save();
        return $this;
    }

    /**
     * Remove stock
     */
    public function removeStock($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Get sold quantity estimate (for orders)
     */
    public function getSoldQuantityAttribute()
    {
        // This would typically come from order_items table
        // For now returning reserved quantity as proxy
        return $this->reserved_quantity;
    }

    /**
     * Get stock status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->stock_status) {
            'out_of_stock' => 'Out of Stock',
            'low_stock' => 'Low Stock',
            'high_stock' => 'High Stock',
            'medium_stock' => 'Medium Stock',
            default => 'Unknown'
        };
    }
}
