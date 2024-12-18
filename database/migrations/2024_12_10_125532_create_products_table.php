<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price_dollars', 10, 2)->nullable();
            $table->decimal('price_soles', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->integer('stock');
            $table->string('serial')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};

