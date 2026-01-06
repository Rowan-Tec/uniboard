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
        $table->boolean('is_rejected')->default(false)->after('is_approved');
        $table->foreignId('rejected_by')->nullable()->after('approved_by')->constrained('users');
        $table->timestamp('rejected_at')->nullable()->after('approved_at');
        $table->text('reject_reason')->nullable()->after('rejected_at'); // optional reason
    });
}

public function down(): void
{
    Schema::table('notices', function (Blueprint $table) {
        $table->dropColumn(['is_rejected', 'rejected_by', 'rejected_at', 'reject_reason']);
    });
}
};
