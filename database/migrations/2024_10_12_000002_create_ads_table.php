<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Связь с пользователем
            $table->string('title');  // Название объявления (для удобства поиска)
            $table->text('description');  // Описание товара
            $table->string('image')->nullable();  // Путь к изображению
            $table->decimal('price', 8, 2);  // Стоимость
            $table->string('location');  // Местонахождение
            $table->string('delivery');  // Условия доставки
            $table->string('phone');  // Телефон
            $table->string('email');  // Email
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};