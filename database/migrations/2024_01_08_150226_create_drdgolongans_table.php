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
        Schema::create('drdgolongans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rute_id')->nullable();
            $table->foreign('rute_id')->references('id')->on('rutes')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('golongan_id');
            $table->foreign('golongan_id')->references('id')->on('golongans')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('jumpel');
            $table->integer('jumm3');
            $table->integer('harga_air');
            $table->integer('adm');
            $table->integer('total');
            $table->integer('status_update')->default(0);
            $table->unique(['rute_id', 'golongan_id', 'bulan', 'tahun']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drdgolongans');
    }
};
