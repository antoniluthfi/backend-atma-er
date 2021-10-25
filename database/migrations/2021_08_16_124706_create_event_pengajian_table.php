<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventPengajianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_pengajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id');
            $table->string('nama_event');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->integer('persentase_kehadiran_lk')->default(0);
            $table->integer('persentase_kehadiran_cw')->default(0);
            $table->integer('total_persentase_kehadiran')->default(0);
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
        Schema::dropIfExists('event_pengajian');
    }
}
