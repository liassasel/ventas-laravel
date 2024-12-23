<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'cliente_nombre')) {
                $table->string('cliente_nombre')->after('status');
            }
            if (!Schema::hasColumn('sales', 'cliente_telefono')) {
                $table->string('cliente_telefono')->nullable()->after('cliente_nombre');
            }
            if (!Schema::hasColumn('sales', 'cliente_correo')) {
                $table->string('cliente_correo')->nullable()->after('cliente_telefono');
            }
            if (!Schema::hasColumn('sales', 'cliente_ruc')) {
                $table->string('cliente_ruc')->nullable()->after('cliente_correo');
            }
            if (!Schema::hasColumn('sales', 'cliente_dni')) {
                $table->string('cliente_dni')->nullable()->after('cliente_ruc');
            }
            if (!Schema::hasColumn('sales', 'numero_guia')) {
                $table->string('numero_guia')->after('cliente_dni');
            }
            if (!Schema::hasColumn('sales', 'fecha_facturacion')) {
                $table->datetime('fecha_facturacion')->after('numero_guia');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'cliente_nombre',
                'cliente_telefono',
                'cliente_correo',
                'cliente_ruc',
                'cliente_dni',
                'numero_guia',
                'fecha_facturacion'
            ]);
        });
    }
};