<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if super admin already exists
        if (! User::where('account_type', 'super_admin')->exists()) {
            User::create([
                'first_name' => Crypt::encrypt('System'),
                'last_name' => Crypt::encrypt('Admin'),
                'email' => Crypt::encrypt('superadmin@usep.edu.ph'),
                'email_hash' => hash('sha256', 'superadmin@usep.edu.ph'),
                'password' => Hash::make('!2Qwerty'),
                'account_type' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'permissions' => implode(', ', config('permissions.super_admin')),
                'program_id' => null,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the super admin if it was created by this migration
        User::where('email_hash', hash('sha256', 'superadmin@usep.edu.ph'))->where('account_type', 'super_admin')->delete();
    }
};
