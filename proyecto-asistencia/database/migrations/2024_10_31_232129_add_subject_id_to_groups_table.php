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
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            

        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            // Agregar nuevamente el campo `subject` en caso de revertir la migración

            
            // Eliminar la clave foránea y la columna `subject_id`
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};
