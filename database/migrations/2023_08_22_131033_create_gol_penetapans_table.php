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
        Schema::create('gol_penetapans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('restrict')->onUpdate('cascade');
            $table->string('aktif');
            $table->integer("harga");
            $table->dateTime("tgl_awal");
            $table->dateTime("tgl_akhir");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gol_penetapans');
    }
};
