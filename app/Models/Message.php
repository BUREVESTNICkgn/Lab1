<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Импорт для fromUser() и toUser()
use App\Models\Product; // Импорт для product()

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['from_user_id', 'to_user_id', 'product_id', 'body'];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}