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
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('cabang_id');
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('restrict')->onUpdate('cascade');
            $table->date("tanggal");
            $table->time("jam_masuk")->nullable();
            $table->time("jam_keluar")->nullable();
            $table->enum("status", ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->string("keterangan")->nullable();
            $table->string("lokasi_masuk")->nullable();
            $table->string("lokasi_keluar")->nullable();
            $table->string("foto_masuk")->nullable();
            $table->string("foto_keluar")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absens');
    }
};
