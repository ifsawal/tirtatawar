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
        Schema::create('pdams', function (Blueprint $table) {
            $table->id();
            $table->string('pdam');
            $table->string('nama')->nullable();
            $table->string('ttd')->nullable();
            $table->unsignedBigInteger('kabupaten_id')->nullable();
            $table->foreign('kabupaten_id')->references('id')->on('kabupatens')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdams');
    }
};
