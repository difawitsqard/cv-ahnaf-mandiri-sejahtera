<?php

namespace App\Models;

use App\Models\MenuImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'name',
        'price',
        'description',
    ];

    protected $appends = [
        'max_order_quantity',
    ];

    public function menuImages()
    {
        return $this->hasMany(MenuImage::class);
    }

    public function stockItems()
    {
        return $this->belongsToMany(StockItem::class, 'menu_stock_items')->withPivot('quantity');
    }

    /**
     * Calculate the maximum order quantity based on available stock items.
     *
     * @return int
     */
    public function getMaxOrderQuantityAttribute()
    {
        $maxOrderQuantity = PHP_INT_MAX;

        foreach ($this->stockItems as $stockItem) {
            $availableStock = $stockItem->stock;
            $requiredQuantity = $stockItem->pivot->quantity;

            if ($requiredQuantity > 0) {
                $maxOrderQuantity = min($maxOrderQuantity, intdiv($availableStock, $requiredQuantity));
            }
        }

        return $maxOrderQuantity;
    }
}
