<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('description', 10);
            $table->timestamps();
        });

        DB::table('statuses')->insert([
          'description' => 'Join' 
        ]);

        DB::table('statuses')->insert([
          'description' => 'Waiting' 
        ]);

        DB::table('statuses')->insert([
          'description' => 'Paid' 
        ]);

        DB::table('statuses')->insert([
          'description' => 'Unpaid' 
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
