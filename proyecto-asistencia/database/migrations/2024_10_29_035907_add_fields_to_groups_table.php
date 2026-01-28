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
            $table->string('subject')->nullable()->after('profesor_id'); // Materia
            $table->string('class_days')->nullable()->after('subject'); // Días de clases
            $table->string('class_schedule')->nullable()->after('class_days'); // Horario de clases
            $table->string('school_period')->nullable()->after('class_schedule'); // Periodo escolar
            $table->boolean('tolerance')->default(false)->after('school_period'); // Tolerancia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['subject', 'class_days', 'class_schedule', 'school_period', 'tolerance']);
        });
    }
};
