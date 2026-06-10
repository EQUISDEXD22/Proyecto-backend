<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void {
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('ruta', 255);              
            $table->string('nombre_original', 255); 
            $table->unsignedBigInteger('formulario_id');
            $table->timestamps();
            $table->foreign('formulario_id')->references('id')->on('formularios')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('imagenes');
    }
};
