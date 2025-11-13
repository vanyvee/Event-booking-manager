<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('event_id');
        $table->string('type'); // Regular, VIP, etc.
        $table->integer('price');
        $table->integer('quantity'); // total available for this ticket type
        $table->integer('available_quantity');
        $table->timestamps();

        $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
