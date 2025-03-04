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
        Schema::create('rekaps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wiljalan_id')->nullable();
            $table->foreign('wiljalan_id')->references('id')->on('wiljalans')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('jumlah_pel');
            $table->integer('jumlah_pel_catat');
            $table->integer('pelanggan_terbayar');
            $table->integer('pemakaian');
            $table->integer('harga_air');
            $table->integer('adm');
            $table->integer('pajak');
            $table->integer('total');
            $table->integer('status_update')->default(0);
            $table->integer('total');
            $table->integer('terbayar');
            $table->integer('sisa');
            $table->integer('persentase');
            $table->integer('denda');
            $table->integer('den_terbayar');
            $table->integer('pelanggan_belum_bayar');
            $table->unsignedBigInteger('pdam_id');
            $table->foreign('pdam_id')->references('id')->on('pdams')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['wiljalan_id', 'bulan', 'tahun']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekaps');
    }
};
