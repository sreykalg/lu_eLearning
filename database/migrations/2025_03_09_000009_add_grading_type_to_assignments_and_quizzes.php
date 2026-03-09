<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('grading_type')->default('manual')->after('max_score'); // auto, manual
        });
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('grading_type')->default('auto')->after('type'); // auto, manual
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('grading_type');
        });
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('grading_type');
        });
    }
};
