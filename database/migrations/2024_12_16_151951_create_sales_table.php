<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->string('status');
            $table->string('cliente_nombre');
            $table->string('cliente_telefono')->nullable();
            $table->string('cliente_correo')->nullable();
            $table->string('cliente_ruc')->nullable();
            $table->string('cliente_dni')->nullable();
            $table->string('numero_guia');
            $table->datetime('fecha_facturacion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};

