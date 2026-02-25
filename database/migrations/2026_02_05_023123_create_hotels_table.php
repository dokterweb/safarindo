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
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_hotel');
            $table->string('lokasi_hotel');
            $table->string('kontak_hotel');
            $table->string('email_hotel')->nullable();;
            $table->string('rating_hotel');
            $table->unsignedBigInteger('harga_hotel');
            $table->string('catatan_hotel')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
