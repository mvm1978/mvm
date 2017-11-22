<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id', 10);
            $table->integer('genre_id')->length(10)->unsigned();
            $table->integer('author_id')->length(10)->unsigned();
            $table->integer('upload_user_id')->length(10)->unsigned();
            $table->timestamp('uploaded_on')->useCurrent();
            $table->integer('type_id')->length(10)->unsigned();
            $table->string('title', 100);
            $table->string('description', 255)->nullable();
            $table->integer('length')->length(5)->unsigned();
            $table->string('picture', 100)->unique()->nullable();
            $table->string('source', 100)->unique();
            $table->integer('downloads')->length(8)->unsigned()->default(0);
            $table->integer('upvotes')->length(10)->unsigned()->default(0);
            $table->integer('downvotes')->length(10)->unsigned()->default(0);
            $table->tinyInteger('approved')->default(0);
            $table->timestamp('approve_date')->nullable();
            $table->timestamp('remove_date')->nullable();

            $table->index('genre_id');
            $table->index('author_id');
            $table->index('upload_user_id');
            $table->index('type_id');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}