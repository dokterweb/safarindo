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
        Schema::create('suratcutis', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->unsignedInteger('paket_id'); 
            $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('cascade');
            $table->foreign('jamaah_id')->onDelete('cascade');
            $table->string('nama_kantor');
            $table->string('alamat_kantor');
            $table->string('jabatan');
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
        Schema::dropIfExists('suratcutis');
    }
};
