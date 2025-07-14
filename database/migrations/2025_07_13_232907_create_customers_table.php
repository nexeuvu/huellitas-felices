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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 20)->unique(); // Número de documento único
            $table->enum('tipo_documento', ['DNI', 'RUC', 'CE', 'PASAPORTE']); // Tipo de documento
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('fecha_registro');
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index(['nombres', 'apellidos']);
            $table->index('dni');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
