<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'stock_item_id',
        'quantity',
        'price',
        'subtotal',
        'description',
        'image_path',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id', 'id');
    }
}
