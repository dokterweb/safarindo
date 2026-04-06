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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('supplier_id'); 
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedInteger('paket_id'); 
            $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('tax'); 
            $table->integer('diskon'); 
            $table->integer('shipping'); 
            $table->bigInteger('grand_total'); 
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
        Schema::dropIfExists('pembelians');
    }
};
