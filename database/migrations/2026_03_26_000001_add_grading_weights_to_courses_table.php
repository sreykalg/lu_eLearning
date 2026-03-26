<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedTinyInteger('quiz_weight')->default(20)->after('level');
            $table->unsignedTinyInteger('assignment_weight')->default(20)->after('quiz_weight');
            $table->unsignedTinyInteger('midterm_weight')->default(30)->after('assignment_weight');
            $table->unsignedTinyInteger('final_weight')->default(30)->after('midterm_weight');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['quiz_weight', 'assignment_weight', 'midterm_weight', 'final_weight']);
        });
    }
};
