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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('breed_id');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('breed_id')
                ->references('id')
                ->on('breeds')
                ->onDelete('cascade');

            // Campos de la mascota
            $table->string('nombre');
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['Macho', 'Hembra']);
            $table->string('color')->nullable();
            $table->decimal('peso', 5, 2)->nullable(); // hasta 999.99 kg
            $table->string('foto')->nullable(); // path o nombre del archivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
