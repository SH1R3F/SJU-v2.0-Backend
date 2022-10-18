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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->nullable()->constrained()->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->date('post_date');
            $table->json('photos')->nullable();
            $table->string('summary_ar');
            $table->string('summary_en')->nullable();
            $table->longtext('content_ar');
            $table->longtext('content_en')->nullable();
            $table->integer('status')->default(1);
            $table->boolean('trash')->default(0);
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
        Schema::dropIfExists('blog_posts');
    }
};
