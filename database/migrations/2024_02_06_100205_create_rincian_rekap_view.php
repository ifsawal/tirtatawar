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
        \DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement($this->dropView());
    }


    private function createView(): string

    {

        return "CREATE VIEW rincian_rekap_view AS

                SELECT 
                    penagihs.id,
                    penagihs.user_id,
                    penagihs.waktu,
                    tagihans.id as tagihan_id,
                    tagihans.pencatatan_id,
                    tagihans.jumlah, 
                    tagihans.diskon, 
                    tagihans.biaya,  
                    tagihans.pajak, 
                    tagihans.denda, 
                    tagihans.total_nodenda, 
                    tagihans.total, 
                    tagihans.status_bayar,
                    tagihans.sistem_bayar,
                    tagihans.tgl_bayar 
        FROM penagihs 
        JOIN tagihans on penagihs.tagihan_id=tagihans.id";



            //  SQL;

    }

   

    /**

     * Reverse the migrations.

     *

     * @return void

     */

    private function dropView(): string

    {

        return "DROP VIEW IF EXISTS rincian_rekap_view";

            //  SQL;

    }
};
