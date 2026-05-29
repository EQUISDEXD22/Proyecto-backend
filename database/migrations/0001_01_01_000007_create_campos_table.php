<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campos', function (Blueprint $table) {
            $table->id();
            $table->string('etiqueta', 150);
            $table->enum('tipo_dato', ['texto', 'numero', 'fecha', 'select', 'textarea']);
            $table->boolean('obligatorio')->default(false);
            $table->integer('orden')->default(0);
            $table->unsignedBigInteger('tipo_formulario_id');
            $table->timestamps();
            $table->foreign('tipo_formulario_id')->references('id')->on('tipos_formularios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campos');
    }
};
