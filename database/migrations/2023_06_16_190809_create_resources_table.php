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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title')->nullable();
            $table->text('content');
            $table->string('url')->nullable();
            $table->enum('category', [
                'memory',
                'note',
                'link'
            ]);
            $table->json('tags');
            $table->timestamps();

            $table->index('uuid');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
