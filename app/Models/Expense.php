<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory, LogsActivity;

    protected static $logName = 'expense_log';
    protected static $logFillable = true;

    protected $fillable = [
        'name',
        'description',
        'date_out',
        'total',
        'status',
        'batch_uuid',
        'user_id',
        'outlet_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('expense_log')
            // ->dontLogIfAttributesChangedOnly(['stock', 'total_stock', 'updated_at'])
            ->logFillable();
        // Chain fluent methods for configuration options
    }

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
