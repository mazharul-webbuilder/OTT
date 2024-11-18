<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailablePageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_page_items', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['Live', 'Tv Show', 'Movie','Sports'])->nullable();
            $table->unsignedBigInteger('item_id');
            $table->string('item_title');
            $table->integer('sort_position')->default(0);
            $table->enum('content_order', ['Ascending', 'Descending'])->nullable();
            $table->boolean('is_active')->default(0);
            $table->string('version_number')->nullable();
            $table->string('order_by_custom_content_id')->nullable();
            $table->string('page_name')->nullable();
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
        Schema::dropIfExists('available_page_items');
    }
}
