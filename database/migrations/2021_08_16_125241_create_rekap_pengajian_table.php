<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapPengajianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekap_pengajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_pengajian_id');
            $table->integer('total_hadir');
            $table->integer('total_izin');
            $table->integer('total_sakit');
            $table->integer('total_alpa');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rekap_pengajian');
    }
}
