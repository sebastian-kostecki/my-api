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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name')->unique();
            $table->string('icon')->nullable();
            $table->string('model');
            $table->string('shortcut')->nullable();
            $table->text('system_prompt')->nullable();
            $table->boolean('enabled')->default(1);
            $table->boolean('hidden')->default(0);
            $table->timestamps();

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
