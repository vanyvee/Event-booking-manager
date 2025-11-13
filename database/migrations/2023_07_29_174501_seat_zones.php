<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seat_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_type_id')->nullable()->constrained('ticket_types')->onDelete('set null');
            $table->string('name'); // e.g. VIP Area, Balcony, etc.
            $table->integer('total_seats');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_zones');
    }
};