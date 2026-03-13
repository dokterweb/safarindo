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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->date('tgl_berangkat');
            $table->mediumInteger('jlh_hari')->unsigned();
            $table->enum('status', ['active', 'completed']);
            $table->string('maskapai_id');
            $table->enum('rute', ['direct', 'transit']);
            $table->string('lokasi_berangkat');
            $table->mediumInteger('kuota')->unsigned();
            $table->string('jenis_paket');
            $table->string('hotel_makah_id');
            $table->string('hotel_madinah_id');
            $table->string('hotel_transit_id');
            $table->unsignedBigInteger('harga_paket');
            $table->text('include_desc')->nullable();
            $table->text('exclude_desc')->nullable();
            $table->text('syaratketentuan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto_paket')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
