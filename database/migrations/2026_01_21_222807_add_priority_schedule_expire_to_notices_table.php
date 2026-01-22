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
    Schema::table('notices', function (Blueprint $table) {
        $table->string('priority')->default('normal')->after('category'); // normal, important, emergency
        $table->timestamp('scheduled_at')->nullable()->after('priority');
        $table->timestamp('expires_at')->nullable()->after('scheduled_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            //
        });
    }
};
