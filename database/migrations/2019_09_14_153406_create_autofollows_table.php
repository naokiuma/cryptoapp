<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutofollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autofollows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('screen_name');
            $table->string('twitter_id');
            $table->string('name');
            $table->string('text');
            $table->datetime('registtime');
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
        Schema::dropIfExists('autofollows');
    }
}
