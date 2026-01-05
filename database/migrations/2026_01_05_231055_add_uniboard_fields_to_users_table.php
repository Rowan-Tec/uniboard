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
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_number')->unique()->after('name');
$table->string('department')->after('id_number');
$table->string('year')->after('department');
$table->enum('gender', ['male', 'female', 'other'])->after('year');
$table->string('phone')->nullable()->after('gender');
$table->string('profile_photo_path')->nullable()->after('phone');
$table->enum('role', ['student', 'staff', 'admin'])->default('student')->after('profile_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
