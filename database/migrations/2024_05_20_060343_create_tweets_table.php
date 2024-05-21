<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMessageTypeInTweetsTable extends Migration
{
    public function up()
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->enum('message_type', ['text', 'image', 'video', 'link'])->change();
        });
    }

    public function down()
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->string('message_type')->change();
        });
    }
}

