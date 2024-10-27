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

    public function menuImages()
    {
        return $this->hasMany(MenuImage::class);
    }

    public function stockItems()
    {
        return $this->belongsToMany(StockItem::class, 'menu_stock_items')->withPivot('quantity');
    }
}
