<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('degree', ['Undergraduate', 'Graduate']);
            $table->timestamps();
        });

        if (DB::table('programs')->count() === 0) {
            DB::table('programs')->insert([
                // Undergraduate Programs
                ['name' => 'Bachelor of Elementary Education', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Science in Information Technology Major in Information Security', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Technical-Vocational Teacher Education', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Early Childhood Education', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Special Needs Education', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Secondary Education Major in Mathematics', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Secondary Education Major in English', 'degree' => 'Undergraduate'],
                ['name' => 'Bachelor of Secondary Education Major in Filipino', 'degree' => 'Undergraduate'],

                // Graduate Programs
                ['name' => 'Doctor of Education Major in Educational Management', 'degree' => 'Graduate'],
                ['name' => 'Master of Education in Educational Management', 'degree' => 'Graduate'],
                ['name' => 'Master of Education in Language Teaching Major in English', 'degree' => 'Graduate'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First drop foreign key constraints from other tables
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });
        Schema::dropIfExists('programs');
    }
};
