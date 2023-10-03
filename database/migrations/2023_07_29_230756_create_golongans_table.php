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
        Schema::create('golongans', function (Blueprint $table) {
            $table->id();
            $table->string("golongan");
            $table->string("jenis");
            $table->integer("harga");
            $table->integer("biaya");
            $table->integer("pajak");
            $table->integer("denda");
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
        Schema::dropIfExists('golongans');
    }
};
