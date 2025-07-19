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
        Schema::create('tour_destinations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->text('name')->unique();
            $table->text('slug')->unique();
            $table->text('link')->nullable();
            $table->text('location')->nullable();
            $table->string('category')->nullable();
            $table->text('short_content')->nullable();
            $table->longText('content')->nullable();
            $table->text('featured_image')->nullable();

            $table->unsignedInteger('been_here')->default(0);
            $table->unsignedInteger('wants_to_count')->default(0);
            $table->unsignedInteger('added_to_list')->default(0);

            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('status_id')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_destinations');
    }
};
