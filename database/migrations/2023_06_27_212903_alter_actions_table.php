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
        Schema::table('actions', function (Blueprint $table) {
            $table->boolean('enabled')->after('icon')->default(1);
            $table->text('prompt')->after('icon')->nullable();
            $table->string('shortcut')->after('icon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn('shortcut');
            $table->dropColumn('enabled');
            $table->dropColumn('prompt');
        });
    }
};
