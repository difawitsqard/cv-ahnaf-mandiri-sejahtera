<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'unit_id',
        'stock',
        'total_stock',
        'min_stock',
        'image_path',
        'price',
        'outlet_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('stock_item_log')
            ->dontLogIfAttributesChangedOnly(['stock', 'total_stock', 'updated_at'])
            ->logFillable();
        // Chain fluent methods for configuration options
    }

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

    public static function restock($id, $outletId, $quantity)
    {
        $stockItem = self::where('id', $id)
            ->where('outlet_id', $outletId)
            ->firstOrFail();

        $oldStock = $stockItem->stock;
        $stockItem->stock += $quantity;
        $stockItem->total_stock = $quantity > 0 ? $stockItem->total_stock + $quantity : $stockItem->total_stock;
        $stockItem->save();

        activity()
            ->useLog('stock_item_log')
            ->event('restocked')
            ->performedOn($stockItem)
            ->withProperties([
                'qty' => $quantity,
                'old' => [
                    'stock' => $oldStock,
                ],
                'attributes' => [
                    'stock' => $stockItem->stock,
                ],
            ])
            ->log("restocked");

        return $stockItem;
    }

    public static function deductStock($id, $outletId, $quantity)
    {
        $stockItem = self::where('id', $id)
            ->where('outlet_id', $outletId)
            ->firstOrFail();

        $oldStock = $stockItem->stock;
        $stockItem->stock -= $quantity;

        if ($stockItem->stock < 0) {
            $stockItem->stock = 0;
        }
        $stockItem->save();

        activity()
            ->useLog('stock_item_log')
            ->event('deducted')
            ->performedOn($stockItem)
            ->withProperties([
                'qty' => -$quantity,
                'old' => [
                    'stock' => $oldStock,
                ],
                'attributes' => [
                    'stock' => $stockItem->stock,
                ],
            ])
            ->log("deducted");

        return $stockItem;
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
