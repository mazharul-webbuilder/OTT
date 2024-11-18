<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOttContentReviewReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ott_content_review_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ott_content_review_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_helpful')->default(false);
            $table->boolean('inappropriate')->default(false);
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
        Schema::dropIfExists('ott_content_review_reactions');
    }
}
