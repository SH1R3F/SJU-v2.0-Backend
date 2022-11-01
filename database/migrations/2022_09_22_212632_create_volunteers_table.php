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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('national_id')->unique();
            $table->string('fname_ar');
            $table->string('sname_ar');
            $table->string('tname_ar');
            $table->string('lname_ar');
            $table->string('fname_en')->nullable();
            $table->string('sname_en')->nullable();
            $table->string('tname_en')->nullable();
            $table->string('lname_en')->nullable();
            $table->boolean('gender')->comment('0: Male, 1: Female');
            $table->integer('country');
            $table->integer('branch');
            $table->integer('city')->nullable();
            $table->integer('nationality');

            $table->string('qualification')->nullable();
            $table->string('major')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employer')->nullable();
            $table->integer('hearabout')->nullable();

            $table->string('governorate')->nullable();
            $table->text('national_address')->nullable();
            $table->text('address')->nullable();
            $table->json('fields')->nullable();
            $table->string('education')->nullable();
            $table->text('experiences')->nullable();

            $table->string('worktel')->nullable();
            $table->string('worktel_ext')->nullable();
            $table->string('fax')->nullable();
            $table->string('fax_ext')->nullable();

            $table->string('post_box')->nullable();
            $table->string('post_code')->nullable();

            $table->string('mobile');
            $table->string('mobile_key');
            $table->string('email')->unique();
            // Email Activation Columns

            
            $table->string('password');
            $table->boolean('status')->comment('0: Disabled, 1: Active')->default(0);

            $table->string('image')->nullable();
            $table->dateTime('last_seen')->nullable();

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
        Schema::dropIfExists('volunteers');
    }
};
