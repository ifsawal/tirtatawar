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
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
            $table->string("wilayah");
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('desa_id');
            $table->foreign('desa_id')->references('id')->on('desas')->onDelete('restrict')->onUpdate('cascade');
            $table->integer("mulai");
            $table->integer("akhir");
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
        Schema::dropIfExists('wilayahs');
    }
};
