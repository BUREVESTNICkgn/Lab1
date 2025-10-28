<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * Отношение с пользователем (многие к одному).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отношение с категорией (многие к одному).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Отношение с изображениями (один ко многим).
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Отношение с сообщениями (один ко многим).
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Проверка истечения срока действия.
     */
    public function isExpired()
    {
        return $this->expires_at && now() > $this->expires_at;
    }
}