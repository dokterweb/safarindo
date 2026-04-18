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
        Schema::create('agent_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->unsignedInteger('jamaah_id'); 
            $table->unsignedInteger('paket_id'); 
            $table->unsignedBigInteger('jumlah');
            $table->string('bukti_bayar')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_transaksis');
    }
};
