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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id('id_transaksi');

        $table->unsignedBigInteger('id_user');
        $table->unsignedBigInteger('id_customer');

        $table->string('kode_transaksi');
        $table->date('tanggal_transaksi');

        $table->integer('total_harga');
        $table->integer('uang_bayar');
        $table->integer('uang_kembali');

        $table->timestamps();

        $table->foreign('id_user')->references('id')->on('users');
        $table->foreign('id_customer')->references('id_customer')->on('customers');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
