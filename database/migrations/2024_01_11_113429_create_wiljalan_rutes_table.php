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
        Schema::create('wiljalan_rutes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rute_id')->nullable();
            $table->foreign('rute_id')->references('id')->on('rutes')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('wiljalan_id')->nullable();
            $table->foreign('wiljalan_id')->references('id')->on('wiljalans')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiljalan_rutes');
    }
};
