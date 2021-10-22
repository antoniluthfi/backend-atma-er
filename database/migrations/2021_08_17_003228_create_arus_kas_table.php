<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArusKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arus_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_kas_id');
            $table->foreignId('users_id'); // ini untuk penyetor uang
            $table->boolean('jenis');
            $table->integer('nominal');
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
        Schema::dropIfExists('arus_kas');
    }
}
