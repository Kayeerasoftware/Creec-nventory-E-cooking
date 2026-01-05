<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->unique();
            $table->string('support_type')->nullable();
            $table->unsignedBigInteger('support_id')->nullable();
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_name', 'support_type', 'support_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};