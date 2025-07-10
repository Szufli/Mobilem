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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2)->unsigned();
            $table->timestamps();
        });
        Schema::create('announcement_images', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
