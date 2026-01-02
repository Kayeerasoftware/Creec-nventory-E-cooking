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
        Schema::table('appliances', function (Blueprint $table) {
            if (!Schema::hasColumn('appliances', 'icon')) {
                $table->string('icon')->nullable()->after('name');
            }
            if (!Schema::hasColumn('appliances', 'model')) {
                $table->string('model')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('appliances', 'power')) {
                $table->string('power')->nullable()->after('model');
            }
            if (!Schema::hasColumn('appliances', 'sku')) {
                $table->string('sku')->nullable()->after('power');
            }
            if (!Schema::hasColumn('appliances', 'status')) {
                $table->string('status')->default('Available')->after('sku');
            }
            if (!Schema::hasColumn('appliances', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('appliances', 'description')) {
                $table->text('description')->nullable()->after('brand_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appliances', function (Blueprint $table) {
            $table->dropColumn(['icon', 'model', 'power', 'sku', 'status', 'brand_id', 'description']);
        });
    }
};
