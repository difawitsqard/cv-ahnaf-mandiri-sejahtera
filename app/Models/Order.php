<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'outlet_id',
        'total',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            do {
                $id = self::generateCustomId($model->outlet_id);
            } while (self::where('id', $id)->exists());

            $model->id = $id;
        });
    }

    public static function generateCustomId($outletId)
    {
        $date = now()->format('ymd'); // Format tanggal (contoh: 241121)
        $outletId = str_pad($outletId, 3, '0', STR_PAD_LEFT); // Nomor outlet, minimal 3 digit

        // Cari jumlah order pada hari ini
        $countToday = self::whereDate('created_at', now()->toDateString())->count();

        // Generate nomor urut (increment)
        $sequence = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

        return "{$sequence}{$outletId}{$date}";
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
