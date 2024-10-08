<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('review');
            $table->integer('score');
            $table->string('title')->nullable();
            $table->string('cover_img')->nullable();
            $table->longText('description')->nullable();
            $table->text('authors')->nullable();
            $table->tinyText('work_id');
            $table->enum('status', ['processing', 'completed', 'error'])->default('processing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
