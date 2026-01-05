<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appliances', function (Blueprint $table) {
            if (!Schema::hasColumn('appliances', 'voltage')) {
                $table->string('voltage')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'frequency')) {
                $table->string('frequency')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'capacity')) {
                $table->string('capacity')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('appliances', 'dimensions')) {
                $table->string('dimensions')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('appliances', 'warranty')) {
                $table->string('warranty')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'features')) {
                $table->text('features')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'certifications')) {
                $table->text('certifications')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'energy_rating')) {
                $table->string('energy_rating')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'country_origin')) {
                $table->string('country_origin')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'supplier_name')) {
                $table->string('supplier_name')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'supplier_contact')) {
                $table->string('supplier_contact')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'last_maintenance')) {
                $table->date('last_maintenance')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'next_maintenance')) {
                $table->date('next_maintenance')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'maintenance_notes')) {
                $table->text('maintenance_notes')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'manual')) {
                $table->string('manual')->nullable();
            }
            if (!Schema::hasColumn('appliances', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('appliances', function (Blueprint $table) {
            $table->dropColumn([
                'voltage', 'frequency', 'capacity', 'weight', 'dimensions',
                'cost_price', 'warranty', 'location', 'features', 'certifications',
                'energy_rating', 'country_origin', 'supplier_name', 'supplier_contact',
                'last_maintenance', 'next_maintenance', 'maintenance_notes', 'manual', 'notes'
            ]);
        });
    }
};
