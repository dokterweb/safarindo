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
        Schema::create('mitras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_mitra');
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->string('kota');
            $table->enum('kelamin', ['laki-laki', 'perempuan']);
            $table->enum('status', ['active', 'non_active']);
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
        Schema::dropIfExists('mitras');
    }
};
