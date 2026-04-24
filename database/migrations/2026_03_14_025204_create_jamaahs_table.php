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
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nama_jamaah');
            $table->string('no_hp');
            $table->string('kota');
            $table->enum('kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->text('catatan')->nullable();
            $table->string('nama_jamaah_pasport');
            $table->string('no_pasport');
            $table->string('penerbit');
            $table->date('pasport_aktif');
            $table->date('pasport_expired');
            $table->string('foto_jamaah')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('foto_pasport1')->nullable();
            $table->string('foto_pasport2')->nullable();
            $table->integer('paket_id_draft ')->nullable();
            $table->foreignId('paket_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            // $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('cascade');
            // $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->enum('status', ['prospek','aktif', 'pindah_paket', 'batal']);
            $table->enum('lunas', ['0', '1']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
