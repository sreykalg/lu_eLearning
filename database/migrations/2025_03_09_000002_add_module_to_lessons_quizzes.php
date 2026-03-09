<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
        });
    }
};
