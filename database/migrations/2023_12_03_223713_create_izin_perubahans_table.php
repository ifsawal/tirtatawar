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
        Schema::create('izin_perubahans', function (Blueprint $table) {
            $table->id();
            $table->string("tabel");
            $table->string("fild");
            $table->integer('id_dirubah');
            $table->string('dasar');
            $table->string('final');
            $table->string('relasi')->nullable();
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
        Schema::dropIfExists('izin_perubahans');
    }
};
