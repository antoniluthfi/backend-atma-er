<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id');
            $table->string('nama');
            $table->double('total_pemasukan', 15, 0)->default(0);
            $table->double('total_pengeluaran', 15, 0)->default(0);
            $table->double('total_kas', 15, 0)->default(0);
            $table->boolean('status');
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
        Schema::dropIfExists('event_kas');
    }
}
