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
            $table->enum('role', ['admin', 'staff'])->default('admin')->after('email');
            $table->boolean('is_approved')->default(false)->after('role');
            $table->foreignId('laundry_id')->nullable()->constrained()->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['laundry_id']);
            $table->dropColumn(['role', 'is_approved', 'laundry_id']);
        });
    }
};
