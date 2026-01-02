<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false);
            $table->foreignId('forwarded_from')->nullable()->constrained('messages')->onDelete('set null');
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('gif_url')->nullable();
            $table->json('poll_options')->nullable();
            $table->json('poll_votes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['forwarded_from']);
            $table->dropColumn(['is_pinned', 'forwarded_from', 'location_lat', 'location_lng', 'contact_name', 'contact_phone', 'gif_url', 'poll_options', 'poll_votes']);
        });
    }
};
