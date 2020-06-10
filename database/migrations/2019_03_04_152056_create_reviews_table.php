<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            // table structure
            $table->increments( 'id' );
            $table->unsignedInteger( 'review_item_id' );
            $table->unsignedInteger( 'user_id' )->nullable();
            $table->unsignedTinyInteger( 'rating' );
            $table->string( 'review_title' );
            $table->string( 'review_content' );
            $table->string( 'reviewer_name' )->nullable();
            $table->timestamps();

            // indexes
            $table->index('review_item_id');
            $table->index(['review_item_id', 'rating']);
            $table->index(['review_item_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
