<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('technical_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_dni')->nullable();
            $table->string('client_ruc')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('guide_number')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->string('serial_number');
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('hard_drive')->nullable();
            $table->text('diagnosis');
            $table->text('problem');
            $table->text('solution')->nullable();
            $table->decimal('service_price', 10, 2);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delivered']);
            $table->enum('repair_status', ['pending', 'in_progress', 'repaired', 'unrepairable']);
            $table->enum('delivery_status', ['not_delivered', 'delivered']);
            $table->datetime('order_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('technical_services');
    }
};

