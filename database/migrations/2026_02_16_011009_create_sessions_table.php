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
    Schema::create('sessions', function (Blueprint $table) {
        $table->id('id_session');

        $table->unsignedBigInteger('id_instructors');
        $table->unsignedBigInteger('id_detail');

        $table->date('tanggal_sesi');
        $table->string('pertemuan_ke');
        $table->enum('status_kehadiran',['hadir','tidak_hadir'])->nullable();
        $table->text('catatan')->nullable();

        $table->timestamps();

        $table->foreign('id_instructors')
              ->references('id_instructors')
              ->on('instructors');

        $table->foreign('id_detail')
              ->references('id_detail')
              ->on('detail_transaksis');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
