<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->string('valor', 200)->nullable();
            $table->unsignedBigInteger('formulario_id');
            $table->unsignedBigInteger('campo_id');
            $table->timestamps();
            $table->foreign('formulario_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->foreign('campo_id')->references('id')->on('campos')->onDelete('cascade');
            $table->unique(['formulario_id', 'campo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
