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
        Schema::create('produks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_produk');
            $table->integer('standar_stok')->default(0);
            $table->integer('stok')->default(0);
            $table->unsignedInteger('unit_id'); 
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->bigInteger('harga_beli')->default(0);
            $table->bigInteger('harga_jual')->default(0);
            $table->text('catatan')->nullable();
            $table->string('foto_produk')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
