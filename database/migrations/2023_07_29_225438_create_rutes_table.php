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
        Schema::create('rutes', function (Blueprint $table) {
            $table->id();
            $table->string("rute");
            $table->unsignedBigInteger('pdam_id');
            $table->foreign('pdam_id')->references('id')->on('pdams')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(array('rute', 'pdam_id'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutes');
    }
};
