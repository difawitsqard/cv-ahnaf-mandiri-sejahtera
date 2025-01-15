<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'outlet_id',
        'sub_total',
        'discount',
        'tax',
        'total',
        'payment_method',
        'paid',
        'change',
        'status',
        'user_id',
        'batch_uuid',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('order_log')
            // ->dontLogIfAttributesChangedOnly(['stock', 'total_stock', 'updated_at'])
            ->logFillable();
        // Chain fluent methods for configuration options
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

    /**
     * Cek apakah pesanan bisa dibatalkan dan pengguna memiliki izin untuk membatalkan pesanan.
     *
     * @param User $user
     * @return bool
     */
    public function canBeCanceled($user)
    {
        if ($user->id ===  $this->user_id || $user->hasRole('superadmin')) {
            if ($this->status != "completed" || $this->created_at->diffInHours(now()) > 2) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Get the "can_be_canceled" attribute.
     *
     * @return bool
     */
    public function getCanBeCanceledAttribute()
    {
        return $this->canBeCanceled(Auth::user());
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
