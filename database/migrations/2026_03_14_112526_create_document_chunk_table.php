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
        Schema::create('document_chunk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_documento");
            $table->integer("num_pag");
            $table->text("contenido");
            $table->text("respuesta")->nullable();
            $table->timestamps();

            $table->foreign("id_documento")->on("document")->references("id")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_chunk');
    }
};
