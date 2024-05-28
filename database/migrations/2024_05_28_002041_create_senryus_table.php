<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('senryus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_name', 128);
            $table->string('theme', 128)->nullable();
            $table->string('s_text1', 128)->nullable();
            $table->string('s_text2', 128)->nullable();
            $table->string('s_text3', 128)->nullable();
            $table->string('img_path', 255)->default('');
            $table->integer('iine');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('senryus');
    }
};
