<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Product; // Импорт для product()

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'path'];

    protected $appends = ['url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        $normalizedPath = str_replace('\\', '/', ltrim($this->path, '/'));
        $storagePath = 'storage/' . $normalizedPath;

        if (Storage::disk('public')->exists($normalizedPath)) {
            return asset($storagePath);
        }

        $publicPath = public_path($normalizedPath);
        if (file_exists($publicPath)) {
            return asset($normalizedPath);
        }

        return 'https://placehold.co/600x400?text=Нет+фото';
    }
}