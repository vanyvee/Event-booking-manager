<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // VIP, Regular, etc.
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_until')->nullable();
            $table->integer('max_per_user')->default(5);
            $table->integer('total_quantity')->default(0);
            $table->integer('sold_quantity')->default(0);
            $table->boolean('is_seated')->default(false); // if seats are required
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};