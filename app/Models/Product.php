<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Импорт для user()
use App\Models\Category; // Импорт для category()
use App\Models\Image; // Импорт для images()
use App\Models\Message; // Импорт для messages()

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'location',
        'delivery',
        'phone',
        'email',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isExpired()
    {
        return $this->expires_at && now() > $this->expires_at;
    }
}