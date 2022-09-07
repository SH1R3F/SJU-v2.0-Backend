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
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('region');
            $table->foreignIdFor(Type::class);
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Gender::class);
            $table->foreignIdFor(Location::class);
            $table->string('map_link');
            $table->string('map_latitude');
            $table->string('map_longitude');
            $table->integer('seats')->unsigned();
            $table->date('date_from');
            $table->date('date_to');
            $table->time('time_from');
            $table->time('time_to');
            $table->json('days');
            $table->integer('minutes')->unsigned();
            $table->integer('percentage')->unsigned();
            $table->integer('price')->unsigned();
            $table->json('images');
            $table->string('trainer');
            $table->text('summary');
            $table->text('content');
            $table->string('zoom')->nullable();
            $table->string('youtube')->nullable();
            $table->foreignIdFor(Template::class)->nullable();
            $table->foreignIdFor(Questionnaire::class)->nullable();
            $table->integer('attendance_duration')->unsigned();
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
