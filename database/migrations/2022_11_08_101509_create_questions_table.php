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
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->boolean('type')->default(0)->comment('1 for mcq, 0 for commentable');
            $table->string('answer1')->nullable();
            $table->string('color1')->nullable();
            $table->string('answer2')->nullable();
            $table->string('color2')->nullable();
            $table->string('answer3')->nullable();
            $table->string('color3')->nullable();
            $table->string('answer4')->nullable();
            $table->string('color4')->nullable();
            $table->integer('order')->nullable();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
};
