<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('assignments', 'allow_late_submission')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->boolean('allow_late_submission')->default(false)->after('due_at');
            });
        } else {
            \DB::table('assignments')->whereNull('allow_late_submission')->orWhere('allow_late_submission', true)->update(['allow_late_submission' => false]);
        }
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('allow_late_submission');
        });
    }
};
