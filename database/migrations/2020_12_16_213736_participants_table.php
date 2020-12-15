<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('participants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_id')->constrained('events');
        $table->foreignId('user_id')->nullable()->constrained('users');
        $table->foreignId('team_id')->nullable()->constrained('teams');
        $table->string('name', 100);
        $table->integer('status_id')->constrained('statuses');
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
      Schema::dropIfExists('participants');
    }
}
