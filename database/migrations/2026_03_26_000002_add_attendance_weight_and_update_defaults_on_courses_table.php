<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedTinyInteger('attendance_weight')->default(10)->after('final_weight');
        });

        \DB::table('courses')->update([
            'quiz_weight' => 10,
            'assignment_weight' => 10,
            'midterm_weight' => 30,
            'final_weight' => 40,
            'attendance_weight' => 10,
        ]);
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('attendance_weight');
        });
    }
};
