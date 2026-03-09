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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('approval_status', 20)->default('draft')->after('is_published');
            $table->timestamp('submitted_at')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('submitted_at');
            $table->text('revision_notes')->nullable()->after('approved_at');
        });

        \DB::table('courses')->where('is_published', true)->update(['approval_status' => 'approved', 'approved_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'submitted_at', 'approved_at', 'revision_notes']);
        });
    }
};
