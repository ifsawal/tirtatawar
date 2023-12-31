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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->nullable();
            $table->string('kk')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('nolama')->nullable()->unique();
            $table->unsignedBigInteger('pdam_id');
            $table->foreign('pdam_id')->references('id')->on('pdams')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->foreign('desa_id')->references('id')->on('desas')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('golongan_id');
            $table->foreign('golongan_id')->references('id')->on('golongans')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('wiljalan_id')->nullable();
            $table->foreign('wiljalan_id')->references('id')->on('wiljalans')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedBigInteger('rute_id')->nullable();
            $table->foreign('rute_id')->references('id')->on('rutes')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id_penyetuju')->nullable();
            $table->foreign('user_id_penyetuju')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id_perubahan')->nullable();
            $table->foreign('user_id_perubahan')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('hp')->default(0);
            $table->integer('penetapan')->default(0);
            $table->integer('kode')->nullable();
            $table->unsignedBigInteger('user_id_petugas')->nullable();
            $table->foreign('user_id_petugas')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
