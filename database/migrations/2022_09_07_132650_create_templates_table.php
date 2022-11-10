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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file');
            $table->string('file_preview')->nullable();
            $table->string('language');
            $table->string('layout');
            $table->json('fields')->nullable();
            $table->boolean('with_title')->default(0);
            $table->string('male_title')->default('')->nullable();
            $table->string('female_title')->default('')->nullable();
            $table->string('certcode')->default('none');
            $table->integer('code_margin_top')->default(0);
            $table->integer('code_margin_right')->default(0);
            $table->integer('code_margin_bottom')->default(0);
            $table->integer('code_margin_left')->default(0);
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
        Schema::dropIfExists('templates');
    }
};
