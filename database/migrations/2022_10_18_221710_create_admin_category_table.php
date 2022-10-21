<?php

use App\Models\Admin;
use App\Models\BlogCategory;
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
        Schema::create('admin_category', function (Blueprint $table) {
            $table->foreignIdFor(Admin::class)->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(BlogCategory::class)->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_category');
    }
};
