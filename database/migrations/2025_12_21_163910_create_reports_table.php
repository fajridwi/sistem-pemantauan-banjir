<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_reports_table.php
public function up()
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');
        $table->string('title');
        $table->text('description');
        $table->string('photo')->nullable();
        $table->decimal('latitude',10,7)->nullable();
        $table->decimal('longitude',10,7)->nullable();
        $table->string('address')->nullable();
        $table->enum('status',[
            'pending','selesai','batal'
        ])->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
