<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_packages', function (Blueprint $table) {
            $table->id('id_paket');

            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_mapel')->nullable();
            $table->unsignedBigInteger('id_instructors')->nullable();

            $table->string('nama');
            $table->string('mata_pelajaran')->nullable();
            $table->integer('harga');
            $table->integer('biaya_sertifikat')->default(0);
            $table->text('deskripsi')->nullable();

            $table->json('hari');
            
            // ✅ TAMBAHKAN KOLOM JAM MULAI DAN JAM SELESAI
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            
            $table->integer('masa_aktif_hari');
            $table->integer('durasi_per_sesi');
            $table->integer('kuota_peserta')->default(0);

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('kategori_id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_packages');
    }
};