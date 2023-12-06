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
        Schema::create('penagih_hapuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->integer("jumlah");
            $table->dateTime("waktu");
            $table->unsignedBigInteger('tagihan_id');
            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id_penghapus');
            $table->foreign('user_id_penghapus')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id_izinhapus');
            $table->foreign('user_id_izinhapus')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penagih_hapuses');
    }
};
