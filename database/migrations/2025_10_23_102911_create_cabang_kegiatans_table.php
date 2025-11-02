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
        Schema::create('cabang_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('lokasi');
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->integer('radius');
            $table->date("tanggal");
            $table->time("jam_mulai");
            $table->time("jam_selesai");
            $table->enum("status", ['buka', 'tutup'])->default('buka');
            $table->unsignedBigInteger('user_id_dibuat')->nullable();
            $table->foreign('user_id_dibuat')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang_kegiatans');
    }
};
