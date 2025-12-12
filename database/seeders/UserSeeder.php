<?php

// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get program IDs
        $undergradPrograms = Program::undergraduate()->pluck('id');
        $gradPrograms = Program::graduate()->pluck('id');
        $allPrograms = $undergradPrograms->merge($gradPrograms);

        // Create students
        User::factory()
            ->count(50)
            ->student()
            ->create(['program_id' => fn () => $undergradPrograms->random()]);

        User::factory()
            ->count(20)
            ->student()
            ->create(['program_id' => fn () => $gradPrograms->random()]);
    }
}
