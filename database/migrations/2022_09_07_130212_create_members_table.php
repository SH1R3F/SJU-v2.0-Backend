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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer('national_id')->unique();
            $table->string('source')->nullable();
            $table->date('date')->nullable();
            $table->string('fname_ar');
            $table->string('sname_ar');
            $table->string('tname_ar');
            $table->string('lname_ar');
            $table->string('fname_en');
            $table->string('sname_en');
            $table->string('tname_en');
            $table->string('lname_en');
            $table->boolean('gender')->comment('0: Male, 1: Female');
            $table->integer('nationality');
            $table->date('birthday_hijri');
            $table->date('birthday_meladi');
            $table->string('qualification');
            $table->string('major');
            $table->string('journalist_job_title');
            $table->string('journalist_employer');
            $table->integer('newspaper_type');
            $table->string('job_title');
            $table->string('employer');

            // Contact info
            $table->string('worktel')->nullable();
            $table->string('worktel_ext')->nullable();
            $table->string('fax')->nullable();
            $table->string('fax_ext')->nullable();

            $table->string('post_box')->nullable();
            $table->string('post_code')->nullable();

            $table->string('mobile')->unique();
            $table->string('email')->unique();
            $table->integer('city');

            // Experiences and fields
            $table->json('experiences_and_fields')->nullable();

            // Files
            $table->string('profile_image')->nullable();
            $table->string('national_image')->nullable();
            $table->string('employer_letter')->nullable();

            // To be updated options
            $table->boolean('updated_personal_information')->default(0);
            $table->boolean('updated_profile_image')->default(0);
            $table->boolean('updated_national_image')->default(0);
            $table->boolean('updated_employer_letter')->default(0);
            $table->boolean('updated_experiences_and_fields')->default(0);

            // Membership information
            $table->string('membership_number')->nullable();
            $table->integer('membership_type')->nullable();
            $table->date('membership_start_date')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->bigInteger('invoice_id')->nullable(); // To be updated to a foreign id when invoices are created *UNFINISHED WORK*
            $table->boolean('invoice_status')->default(0);
            $table->integer('status')->default(0);

            $table->string('password');
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
        Schema::dropIfExists('members');
    }
};
