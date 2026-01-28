<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            // Agrega la columna 'qr_interval' de tipo entero con valor por defecto de 30
            $table->integer('qr_interval')->default(30)->after('tolerance')->comment('Intervalo para la validez del código QR en minutos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            // Elimina la columna 'qr_interval'
            $table->dropColumn('qr_interval');
        });
    }
};
