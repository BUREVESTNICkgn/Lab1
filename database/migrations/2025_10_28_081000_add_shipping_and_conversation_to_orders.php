<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_method')->default('delivery')->after('total');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_method');
            $table->string('shipping_address')->nullable()->after('shipping_cost');
            $table->string('contact_phone')->nullable()->after('shipping_address');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade')->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_method', 'shipping_cost', 'shipping_address', 'contact_phone']);
        });
    }
};
