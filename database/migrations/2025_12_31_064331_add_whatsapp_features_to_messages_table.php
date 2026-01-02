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
            $table->string('type')->default('text'); // text, image, video, audio, document
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('status')->default('sent'); // sent, delivered, read
            $table->foreignId('reply_to')->nullable()->constrained('messages')->onDelete('set null');
            $table->string('reaction')->nullable();
            $table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to']);
            $table->dropColumn(['type', 'file_path', 'file_name', 'file_size', 'status', 'reply_to', 'reaction', 'is_deleted']);
        });
    }
};
