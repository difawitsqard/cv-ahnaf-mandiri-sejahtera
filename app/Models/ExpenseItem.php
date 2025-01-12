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
}
