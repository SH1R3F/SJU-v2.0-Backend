<?php

use App\Models\TechnicalSupportTicket;
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
        Schema::create('technical_support_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TechnicalSupportTicket::class)->onDelete('cascade')->onUpdate('cascade');
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('sender')->comment('1 => Technical support sent it, 2 => User sent it');
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
        Schema::dropIfExists('technical_support_chats');
    }
};
