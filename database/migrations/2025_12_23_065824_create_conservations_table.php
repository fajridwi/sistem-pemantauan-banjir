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
       Schema::create('conversations', function (Blueprint $table) {
    $table->id();

    $table->foreignId('masyarakat_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('pemerintah_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();

    $table->unique(['masyarakat_id', 'pemerintah_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conservations');
    }
};
