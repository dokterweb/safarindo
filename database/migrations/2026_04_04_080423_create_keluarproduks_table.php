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
        Schema::create('keluarproduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('paket_id'); 
            $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('tax'); 
            $table->integer('diskon'); 
            $table->integer('shipping'); 
            $table->enum('statuskeluar', ['process', 'delivery', 'completed']);
            $table->enum('metodekirim', ['kurir', 'kantor','delivery', 'other']);
            $table->bigInteger('grand_total'); 
            $table->string('alamat')->nullable();
            $table->string('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluarproduks');
    }
};
