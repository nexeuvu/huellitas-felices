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
        Schema::create('breeds', function (Blueprint $table) {
            $table->id();
            // Relación con species
            $table->unsignedBigInteger('species_id');
            $table->foreign('species_id')
                ->references('id')
                ->on('species')
                ->onDelete('cascade');

            // Campos específicos de la raza
            $table->string('nombre');
            $table->text('caracteristicas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeds');
    }
};
