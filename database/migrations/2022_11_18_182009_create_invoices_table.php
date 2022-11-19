<?php

use App\Models\Member;
use App\Models\Subscription;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('cart_ref')->nullable();
            $table->foreignIdFor(Member::class)->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(Subscription::class)->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->string('order_ref')->nullable();
            $table->string('amount')->nullable();
            $table->json('member_data')->nullable();
            $table->json('subscription_data')->nullable();
            $table->json('order_data')->nullable();
            $table->integer('payment_method')->nullable();
            $table->integer('status')->default(0)->comment('0: Unpaid, 1: Paid');
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
        Schema::dropIfExists('invoices');
    }
};
