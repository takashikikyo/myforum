<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('body');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->nullable();
        });

        DB::connection('public')->statement("
            create or replace function set_update_time() returns trigger language plpgsql as
           $$
            begin
              new.updated_at = CURRENT_TIMESTAMP;
              return new;
            end;
           $$;
        ");
      // トリガーの定義
      DB::connection('public')->statement("
          create trigger update_trigger before update on medias for each row
            execute procedure set_update_time();
      ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

