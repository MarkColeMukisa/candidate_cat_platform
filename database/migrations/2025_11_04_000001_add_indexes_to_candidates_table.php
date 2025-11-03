<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table): void {
            // For filtering and grouping
            $table->index('tier');
            // For common sorting
            $table->index('created_at');
            $table->index('name');
            // Email is already unique (indexed) from the create table migration
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table): void {
            $table->dropIndex(['tier']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['name']);
            $table->dropIndex(['phone']);
        });
    }
};
