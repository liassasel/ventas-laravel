<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('unit_price_dollars', 10, 2); // Este es el nombre correcto que ya está usando
            $table->decimal('total_price', 10, 2);
            $table->decimal('total_price_dollars', 10, 2);
            $table->timestamps();

            $table->foreign('shipment_id')
                  ->references('id')
                  ->on('shipments')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_items');
    }
};

