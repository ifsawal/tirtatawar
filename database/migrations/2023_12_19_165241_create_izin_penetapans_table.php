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
        Schema::create('izin_penetapans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('restrict')->onUpdate('cascade');
            $table->string('aktif');
            $table->integer("harga");
            $table->integer("pajak")->default(0);
            $table->dateTime("tgl_awal");
            $table->dateTime("tgl_akhir")->nullable();
            $table->string('alasan')->nullable();
            $table->string('ket')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id_penyetuju')->nullable();
            $table->foreign('user_id_penyetuju')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('pdam_id');
            $table->foreign('pdam_id')->references('id')->on('pdams')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_penetapans');
    }
};
