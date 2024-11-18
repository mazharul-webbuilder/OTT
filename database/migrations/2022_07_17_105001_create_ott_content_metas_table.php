<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOttContentMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ott_content_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id');
            $table->mediumText('key');
            $table->longText('value');
            $table->enum('type',['Tags','Casts'])->nullable();
            $table->foreign('content_id')->references('id')->on('ott_contents')->onDelete('cascade');
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
        Schema::dropIfExists('ott_content_metas');
    }
}
