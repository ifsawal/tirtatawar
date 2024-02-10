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
        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->date("tanggal");
            $table->integer("jumlah");
            $table->integer("dasar");
            $table->integer("denda");
            $table->integer("adm");
            $table->integer("pajak");
            $table->integer("diskon");
            $table->unsignedBigInteger('user_id_diserahkan')->nullable();
            $table->foreign('user_id_diserahkan')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->integer("diterima")->default(0);
            $table->unique(array('user_id', 'tanggal'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
