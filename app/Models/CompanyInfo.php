<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    // Menonaktifkan otomatisasi timestamp (created_at dan updated_at) pada model.
    public $timestamps = false;
    // Menentukan bahwa model ini tidak memiliki primary key.
    protected $primaryKey = null;
    // Menonaktifkan auto-increment pada primary key.
    public $incrementing = false;

    protected $fillable = [
        'name',
        'short_name',
        'tagline',
        'about_us',
        'email',
        'phone',
        'whatsapp',
        'instagram',
        'address',
    ];
}
