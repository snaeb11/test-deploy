<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([ProgramSeeder::class, UserSeeder::class, DownloadableFormSeeder::class]);
    }
}
