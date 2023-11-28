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
        Schema::table('messages', function (Blueprint $table) {
            $table->text('text')->nullable()->change();
            $table->string('remote_id')->nullable()->change();
            $table->json('details')->after('text')->nullable();
            $table->string('status')->after('text');
            $table->timestamp('completed_at')->after('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('details');
            $table->dropColumn('status');
            $table->dropColumn('completed_at');
            $table->text('text')->change();
            $table->string('remote_id')->index();
        });
    }
};
