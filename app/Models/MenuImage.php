<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            $filePath = public_path('uploads/' . $this->image_path);
            if (file_exists($filePath)) {
                return asset('uploads/' . $this->image_path);
            }
        }

        return null;
    }
}
