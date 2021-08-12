<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Offer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title")->require();
            $table->string("activity")->require();
            $table->string("type")->require();
            $table->double("area")->require();
            $table->double("price")->require();
            $table->string("site")->require();
            $table->boolean("negotiable")->require();
            $table->longText("desc")->require();
            $table->string("lat")->require();
            $table->string("lng")->require();

            $table->string("name")->default("none");
            $table->string("phone")->default("none");
            $table->boolean("confirmed")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
