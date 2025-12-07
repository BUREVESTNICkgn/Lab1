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
        $storagePath = 'storage/' . ltrim($this->path, '/');

        if (Storage::disk('public')->exists($this->path)) {
            return asset($storagePath);
        }

        $publicPath = public_path($this->path);
        if (file_exists($publicPath)) {
            return asset($this->path);
        }

        return 'https://placehold.co/600x400?text=Нет+фото';
    }
}