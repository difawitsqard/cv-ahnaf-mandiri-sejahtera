<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Outlet extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = self::generateUniqueSlug($model->name);
            }
        });
    }

    protected $fillable = ['name', 'address', 'phone_number', 'image_path'];

    protected $appends = ['image_url'];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
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

    public function scopeFilter($query)
    {
        $columns = ['name', 'address'];
        $query->when(request('search') ?? false, function ($query, $search) use ($columns) {
            $query->where(function ($query) use ($columns, $search) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
