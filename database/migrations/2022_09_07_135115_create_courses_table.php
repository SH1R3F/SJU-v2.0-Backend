<?php

use App\Models\Course\Type;
use App\Models\Course\Gender;
use App\Models\Course\Category;
use App\Models\Course\Location;
use App\Models\Course\Template;
use App\Models\Course\Questionnaire;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('SN')->unique();
            $table->text('name_ar');
            $table->string('name_en')->nullable();
            $table->string('region');
            $table->foreignIdFor(Type::class)->nullable()->onDelete('set null')->onUpdate('cascade');
            $table->foreignIdFor(Category::class)->nullable()->onDelete('set null')->onUpdate('cascade');
            $table->foreignIdFor(Gender::class)->nullable()->onDelete('set null')->onUpdate('cascade');
            $table->foreignIdFor(Location::class)->nullable()->onDelete('set null')->onUpdate('cascade');
            $table->string('map_link')->nullable();
            $table->string('map_latitude')->nullable();
            $table->string('map_longitude')->nullable();
            $table->integer('seats')->unsigned()->nullable();
            $table->date('date_from');
            $table->date('date_to');
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->json('days')->nullable();
            $table->integer('minutes')->unsigned()->nullable();
            $table->integer('percentage')->unsigned()->nullable();
            $table->integer('price')->unsigned()->nullable();
            $table->json('images')->nullable();
            $table->text('trainer')->nullable();
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->text('zoom')->nullable();
            $table->string('youtube')->nullable();
            $table->foreignIdFor(Template::class)->nullable()->onDelete('set null')->onUpdate('cascade');
            $table->foreignIdFor(Questionnaire::class)->nullable()->onDelete('set null')->onUpdate('cascade');
            $table->integer('attendance_duration')->unsigned()->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('courses');
    }
};
