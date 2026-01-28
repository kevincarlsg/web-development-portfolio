<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustificantesTable extends Migration
{
    public function up()
    {
        Schema::create('justificantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('attendance_id');
            $table->date('fecha');
            $table->string('archivo');
            $table->string('estado')->default('pendiente');
            $table->timestamps();
    
            $table->foreign('alumno_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('justificantes');
    }
}
