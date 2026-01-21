<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movie_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->string('path'); // Caminho do arquivo no storage
            $table->string('filename'); // Nome original do arquivo
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable(); // Tamanho em bytes
            $table->boolean('is_cover')->default(false); // Indica se é a capa principal
            $table->timestamps();

            $table->index(['movie_id', 'is_cover']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_images');
    }
};
