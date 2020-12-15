<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer('event_type');
            $table->string('name', 100);
            $table->string('address', 200);
            $table->date('event_date');
            $table->string('start_time', 10);
            $table->string('end_time', 10);
            $table->smallInteger('min_head')->nullable();
            $table->smallInteger('max_head');
            $table->string('memo', 255)->nullable();
            $table->bigInteger('created_by');
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
        Schema::dropIfExists('events');
    }
}
