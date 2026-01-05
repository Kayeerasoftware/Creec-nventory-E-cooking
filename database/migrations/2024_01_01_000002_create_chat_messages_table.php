<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->string('sender_type'); // user, technician, trainer, admin
            $table->unsignedBigInteger('sender_id');
            $table->text('message')->nullable();
            $table->enum('message_type', ['text', 'image', 'video', 'audio', 'file', 'location'])->default('text');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index(['chat_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};