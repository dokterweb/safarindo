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
        Schema::create('keluarproduk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarproduk_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('produk_id'); 
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
            $table->bigInteger('harga');
            $table->integer('qty'); 
            $table->integer('diskon'); 
            $table->integer('total'); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluarproduk_details');
    }
};
