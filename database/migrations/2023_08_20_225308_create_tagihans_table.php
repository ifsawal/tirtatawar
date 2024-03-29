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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pencatatan_id');
            $table->foreign('pencatatan_id')->references('id')->on('pencatatans')->onDelete('restrict')->onUpdate('cascade');
            $table->unique('pencatatan_id');
            $table->integer("jumlah");
            $table->integer("diskon");
            $table->integer("biaya")->default(0);
            $table->integer("denda_perbulan")->default(0);
            $table->integer("pajak")->default(0);
            $table->integer("denda");
            $table->integer("adm_bank")->nullable();
            $table->integer("subtotal");
            $table->integer("total");
            $table->string("status_bayar");
            $table->string("sistem_bayar")->nullable();
            $table->dateTime("tgl_bayar")->nullable();
            $table->unsignedBigInteger('gol_penetapan_id')->nullable();
            $table->foreign('gol_penetapan_id')->references('id')->on('gol_penetapans')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id_perubahan')->nullable();
            $table->foreign('user_id_perubahan')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->integer("total_nodenda");
            $table->integer("off_denda")->nullable();
            $table->dateTime("bayar_bank")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
