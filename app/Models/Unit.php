<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Temukan atau buat unit baru berdasarkan ID atau nama.
     *
     * @param mixed $identifier ID atau nama unit
     * @return \App\Models\Unit
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
            $unitById = self::find($identifier);
            if ($unitById) {
                return $unitById;
            }
        }

        // Cek berdasarkan nama (case-insensitive)
        $unitByName = self::where(DB::raw('LOWER(name)'), strtolower($identifier))->first();
        if ($unitByName) {
            return $unitByName;
        }

        // Jika tidak ditemukan, buat unit baru
        return self::create(['name' => $identifier]);
    }
}
