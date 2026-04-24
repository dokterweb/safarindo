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
        Schema::create('pembatalans', function (Blueprint $table) {
            $table->increments('id');
            // Relasi utama
            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();

            $table->foreignId('paket_id')
                ->constrained('pakets')
                ->cascadeOnDelete();

            // Paket tujuan jika pemindahan
            $table->foreignId('paket_tujuan_id')
                ->nullable()
                ->constrained('pakets')
                ->nullOnDelete();

            $table->string('no_surat')->nullable();
            // Jenis pengajuan
            $table->enum('jenis', ['pemindahan', 'pembatalan']);

            // Refund jika pembatalan
            $table->bigInteger('pengembalian_uang')->nullable();

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            // Status persetujuan
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])
                ->default('pending');

            // Admin yang menyetujui
            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('disetujui_pada')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembatalans');
    }
};
