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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string("vendor");
            $table->integer("vendor_id")->nullable();
            $table->string("bill_id")->nullable();
            $table->string("va")->nullable();
            $table->string("nama")->nullable();
            $table->string("bank")->nullable();
            $table->string("tipe")->nullable();
            $table->string("vendor_id_string")->nullable();
            $table->integer("jumlah");
            $table->string("url")->nullable();
            $table->string("ket")->nullable();
            $table->string("status_bayar")->default("N");
            $table->string("status_bayar_vendor")->nullable();
            $table->string("manual_by")->nullable();
            $table->unsignedBigInteger('tagihan_id')->nullable();
            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('restrict')->onUpdate('cascade');
            $table->string("tabel_transfer")->nullable();
            $table->unsignedBigInteger('id_tabel')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
