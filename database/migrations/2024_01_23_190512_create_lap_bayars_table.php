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
        Schema::create('lap_bayars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('jumlah_p');
            $table->integer('p_terbayar');
            $table->integer('p_no_bayar');
            $table->integer('total_rp');
            $table->integer('rp_terbayar');
            $table->integer('rp_no_bayar');
            $table->integer('tagih_sendiri');
            $table->integer('drd');
            $table->integer('terbayar_no_denda');
            $table->integer('denda');
            $table->integer('sisa');
            $table->integer('persentase');

            $table->unique(['user_id', 'bulan', 'tahun']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lap_bayars');
    }
};
