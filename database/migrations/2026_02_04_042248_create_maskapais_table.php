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
        Schema::create('maskapais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_maskapai');
            $table->enum('rute_terbang', ['direct', 'transit']);
            $table->unsignedInteger('lama_perjalanan');
            $table->unsignedBigInteger('harga_tiket');
            $table->string('catatan_keberangkatan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maskapais');
    }
};
