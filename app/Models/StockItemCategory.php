<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockItemCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_static'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            if ($category->is_static) {
                throw new \Exception("Kategori statis tidak dapat dihapus.");
            }
        });

        static::updating(function ($category) {
            if ($category->is_static) {
                throw new \Exception("Kategori statis tidak dapat di-update.");
            }
        });
    }

    /**
     * Temukan atau buat unit baru berdasarkan ID atau nama.
     *
     * @param mixed $identifier ID atau nama unit
     * @return \App\Models\StockItemCategory
     */
    public static function findOrCreate($identifier)
    {
        // Validasi input
        if (empty($identifier)) {
            throw new \InvalidArgumentException("Identifier tidak boleh kosong.");
        }

        // Cek apakah identifier adalah ID atau nama
        if (is_numeric($identifier)) {
            // Cek berdasarkan ID
            $categoryById = self::find($identifier);
            if ($categoryById) {
                return $categoryById;
            }
        }

        // Cek berdasarkan nama (case-insensitive)
        $categoryByName = self::where(DB::raw('LOWER(name)'), strtolower($identifier))->first();
        if ($categoryByName) {
            return $categoryByName;
        }

        // Jika tidak ditemukan, buat unit baru
        return self::create(['name' => $identifier]);
    }
}
