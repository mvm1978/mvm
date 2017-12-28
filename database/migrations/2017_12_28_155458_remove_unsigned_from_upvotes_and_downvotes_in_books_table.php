<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class RemoveUnsignedFromUpvotesAndDownvotesInBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function() {
            DB::statement("ALTER TABLE `books` CHANGE `upvotes` `upvotes` INT(10) NOT NULL DEFAULT '0'");
            DB::statement("ALTER TABLE `books` CHANGE `downvotes` `downvotes` INT(10) NOT NULL DEFAULT '0'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function() {
            DB::statement("ALTER TABLE `books` CHANGE `upvotes` `upvotes` INT(10) UNSIGNED NOT NULL DEFAULT '0'");
            DB::statement("ALTER TABLE `books` CHANGE `downvotes` `downvotes` INT(10) UNSIGNED NOT NULL DEFAULT '0'");
        });
    }
}
