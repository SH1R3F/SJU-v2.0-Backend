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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('fname_ar');
            $table->string('sname_ar');
            $table->string('tname_ar');
            $table->string('lname_ar');
            $table->string('fname_en');
            $table->string('sname_en');
            $table->string('tname_en');
            $table->string('lname_en');
            $table->boolean('gender')->comment('0: Male, 1: Female');
            $table->string('country');
            $table->string('city');
            $table->string('nationality');
            $table->date('birthday_hijri');
            $table->date('birthday_meladi');

            $table->string('qualification');
            $table->string('major')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employer')->nullable();

            $table->string('worktel')->nullable();
            $table->string('worktel_ext')->nullable();
            $table->string('fax')->nullable();
            $table->string('fax_ext')->nullable();

            $table->string('post_box')->nullable();
            $table->string('post_code')->nullable();

            $table->string('mobile')->unique();
            $table->string('mobile_key');
            $table->string('email')->unique();
            $table->string('password');

            $table->string('image')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('subscribers');
    }
};
