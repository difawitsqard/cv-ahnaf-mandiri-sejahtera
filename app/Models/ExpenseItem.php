<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseItem extends Model
{
    use HasFactory, LogsActivity;

    protected static $logName = 'expense_item_log';
    protected static $logFillable = true;

    protected $fillable = [
        'expense_id',
        'stock_item_id',
        'name',
        'quantity',
        'price',
        'subtotal',
        'description',
        'image_path',
    ];

    protected $appends = ['image_url'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('expense_item_log')
            ->logFillable();
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id', 'id');
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
}
