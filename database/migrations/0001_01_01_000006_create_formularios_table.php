<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->enum('estado', ['borrador', 'enviado', 'valido', 'denegado'])->default('borrador');
            $table->string('comentarios', 200)->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('tipo_formulario_id');
            $table->timestamps();
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('tipo_formulario_id')->references('id')->on('tipos_formularios')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formularios');
    }
};
