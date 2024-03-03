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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("nama");
            $table->string("alamat")->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('client_id')->unique()->nullable();
            $table->string('password');
            $table->string('kode')->unique()->nullable();
            $table->string('channel')->nullable();
            $table->string('ip')->nullable();
            $table->rememberToken()->nullable();
            $table->unsignedBigInteger('pdam_id')->nullable();
            $table->foreign('pdam_id')->references('id')->on('pdams')->onDelete('restrict')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
