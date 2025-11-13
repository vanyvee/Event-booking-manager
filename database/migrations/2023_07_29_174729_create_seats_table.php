<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_zone_id')->constrained('seat_zones')->onDelete('cascade');
            $table->string('seat_number'); // A1, A2, etc.
            $table->boolean('is_booked')->default(false);
            $table->foreignId('booked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};