<?php

use App\Models\Course\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->foreignIdFor(Course::class)->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('courseable_id');
            $table->string('courseable_type');
            $table->boolean('attendance')->default(0)->comment("0 => Didn't attend, 1 => Attended");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_user');
    }
};
