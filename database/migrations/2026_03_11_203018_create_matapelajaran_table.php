<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matapelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel');
            $table->timestamps();
        });

        // ← Tambah foreign key setelah kedua tabel ada
        Schema::table('course_packages', function (Blueprint $table) {
            $table->foreign('id_mapel')
                  ->references('id')
                  ->on('matapelajarans')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('course_packages', function (Blueprint $table) {
            $table->dropForeign(['id_mapel']);
        });
        Schema::dropIfExists('matapelajarans');
    }
};