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
        Schema::create('drdjalans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rute_id')->nullable();
            $table->foreign('rute_id')->references('id')->on('rutes')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('wiljalan_id')->nullable();
            $table->foreign('wiljalan_id')->references('id')->on('wiljalans')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('golongan_id');
            $table->foreign('golongan_id')->references('id')->on('golongans')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('jumpel');
            $table->integer('jumm3');
            $table->integer('jumtotal');
            $table->integer('status_update')->default(0);
            $table->string('jenis')->nullable();// jenis = hapus jika aktif ==NULL
            $table->unique(['rute_id', 'wiljalan_id', 'golongan_id', 'bulan', 'tahun','jenis']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drdjalans');
    }
};
