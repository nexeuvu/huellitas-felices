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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 20)->unique(); // Número de documento único
            $table->enum('tipo_documento', ['DNI', 'CE', 'PASAPORTE']); // Tipo de documento (quitado RUC que no aplica para empleados)
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable()->unique(); // Email único
            $table->date('fecha_contratacion');
            $table->string('puesto', 50); // Campo específico para empleados
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index(['nombres', 'apellidos']);
            $table->index('dni');
            $table->index('email');
            $table->index('puesto'); // Índice para búsquedas por puesto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
