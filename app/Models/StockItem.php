<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit_id',
        'stock',
        'min_stock',
        'image_path',
        'price',
        'outlet_id',
    ];

    protected $appends = [
        'image_url',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_stock_item')->withPivot('quantity');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            $filePath = public_path('uploads/' . $this->image_path);
            if (file_exists($filePath)) {
                return asset('uploads/' . $this->image_path);
            }
        }

        return asset('build/images/placeholder-image.webp');
    }

    public function scopeFilter($query)
    {
        $columns = ['name', 'stock', 'cost', 'description'];
        $query->when(request('search') ?? false, function ($query, $search) use ($columns) {
            $query->where(function ($query) use ($columns, $search) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
                $query->orWhereHas('unit', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        });
    }
}
