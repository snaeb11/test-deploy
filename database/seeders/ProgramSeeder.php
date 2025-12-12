<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        $programs = [
            // Undergraduate Programs
            ['name' => 'BEEd', 'degree' => 'Undergraduate'],
            ['name' => 'BSIT', 'degree' => 'Undergraduate'],
            ['name' => 'BTVTEd', 'degree' => 'Undergraduate'],
            ['name' => 'BECEd', 'degree' => 'Undergraduate'],
            ['name' => 'BSNEd', 'degree' => 'Undergraduate'],
            ['name' => 'BSEd Mathematics', 'degree' => 'Undergraduate'],
            ['name' => 'BSEd English', 'degree' => 'Undergraduate'],
            ['name' => 'BSEd Filipino', 'degree' => 'Undergraduate'],

            // Graduate Programs
            ['name' => 'EdD', 'degree' => 'Graduate'],
            ['name' => 'MEEM', 'degree' => 'Graduate'],
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate(['name' => $program['name']], $program);
        }
    }
}
