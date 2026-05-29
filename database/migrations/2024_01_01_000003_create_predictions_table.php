<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->integer('score1');
            $table->integer('score2');
            $table->integer('points')->default(0);
            $table->timestamps();
            $table->unique(['user_name', 'match_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
