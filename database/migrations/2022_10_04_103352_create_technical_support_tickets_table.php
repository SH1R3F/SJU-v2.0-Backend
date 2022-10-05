<?php

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
        Schema::create('technical_support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('status')->default(1)->comment('1 => Open ticket, 0 => Solved ticket');
            $table->unsignedBigInteger('ticketable_id');
            $table->string('ticketable_type');
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
        Schema::dropIfExists('technical_support_tickets');
    }
};
