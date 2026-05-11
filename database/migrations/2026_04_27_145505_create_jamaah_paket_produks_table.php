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
        Schema::create('jamaah_paket_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('paket_id'); 
            $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('cascade');
            $table->integer('qty_diambil'); 
            $table->date('tanggal');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaah_paket_produks');
    }
};
