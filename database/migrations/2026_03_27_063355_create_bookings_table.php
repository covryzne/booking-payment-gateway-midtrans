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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        // relasi user
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // data penyewa
        $table->string('nama');
        $table->string('no_hp');

        // booking utama
        $table->date('tanggal');
        $table->time('jam_mulai');
        $table->time('jam_selesai');

        // tambahan
        $table->text('keperluan'); 

        // harga
        $table->integer('durasi_jam');
        $table->integer('total_harga');

        // pembayaran
        $table->enum('status', ['proses', 'lunas'])->default('proses');

        // e-ticket
        $table->string('kode_booking')->unique();
        $table->string('qr_code')->nullable();

        // validasi petugas
        $table->boolean('is_used')->default(false);

        $table->timestamps();
    });
}
};
