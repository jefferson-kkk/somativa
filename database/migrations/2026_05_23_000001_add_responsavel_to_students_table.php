<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'responsavel')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('responsavel')->default('');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'responsavel')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('responsavel');
            });
        }
    }
};
