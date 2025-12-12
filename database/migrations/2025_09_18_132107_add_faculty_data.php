<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert faculty users
        $facultyData = [
            [
                'first_name' => Crypt::encrypt('Lanz'),
                'last_name' => Crypt::encrypt('Manguilimotan'),
                'email' => Crypt::encrypt('lomanguilimotan00277@usep.edu.ph'),
                'email_hash' => hash('sha256', 'lomanguilimotan00277@usep.edu.ph'),
                'password' => Hash::make('!2Qwerty'),
                'account_type' => 'faculty',
                'program_id' => null,
                'status' => 'active',
                'email_verified_at' => now(),
                'permissions' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => Crypt::encrypt('Vincent Kyle'),
                'last_name' => Crypt::encrypt('Arsenio'),
                'email' => Crypt::encrypt('vkoarsenio02205@usep.edu.ph'),
                'email_hash' => hash('sha256', 'vkoarsenio02205@usep.edu.ph'),
                'password' => Hash::make('!2Qwerty'),
                'account_type' => 'student',
                'program_id' => null,
                'status' => 'active',
                'email_verified_at' => now(),
                'permissions' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($facultyData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove faculty users
        $facultyEmails = ['lomanguilmotan00277@usep.edu.ph'];

        DB::table('users')->whereIn('email', $facultyEmails)->delete();
    }
};
