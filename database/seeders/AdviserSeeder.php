<?php

namespace Database\Seeders;

use App\Models\Adviser;
use App\Models\Program;
use Illuminate\Database\Seeder;

class AdviserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing advisers
        Adviser::truncate();

        $programs = Program::all();

        // Sample adviser names for each program
        $adviserNames = [
            'BEEd' => ['Dr. Maria Santos', 'Prof. Juan Dela Cruz', 'Dr. Ana Reyes'],
            'BSIT' => ['Engr. Roberto Garcia', 'Prof. Carmen Lopez', 'Dr. Manuel Torres'],
            'BTVTEd' => ['Prof. Elena Mendoza', 'Dr. Carlos Aquino', 'Prof. Sofia Rivera'],
            'BECEd' => ['Dr. Patricia Cruz', 'Prof. Antonio Santos', 'Dr. Isabel Morales'],
            'BSNEd' => ['Prof. Rosa Martinez', 'Dr. Fernando Reyes', 'Prof. Lucia Gomez'],
            'BSEd Mathematics' => ['Dr. Jose Santos', 'Prof. Maria Garcia', 'Dr. Pedro Lopez'],
            'BSEd English' => ['Prof. Ana Torres', 'Dr. Roberto Cruz', 'Prof. Carmen Santos'],
            'BSEd Filipino' => ['Dr. Manuel Reyes', 'Prof. Elena Garcia', 'Dr. Carlos Santos'],
            'EdD' => ['Dr. Sofia Aquino', 'Prof. Juan Morales', 'Dr. Patricia Torres'],
            'MEEM' => ['Engr. Roberto Santos', 'Prof. Maria Cruz', 'Dr. Antonio Garcia'],
        ];

        foreach ($programs as $program) {
            if (isset($adviserNames[$program->name])) {
                foreach ($adviserNames[$program->name] as $adviserName) {
                    Adviser::create([
                        'name' => $adviserName,
                        'program_id' => $program->id,
                    ]);
                }
            }
        }
    }
}
