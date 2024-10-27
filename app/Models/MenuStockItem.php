<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuStockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'stock_item_id',
        'quantity',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id', 'id');
    }
}
