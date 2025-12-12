<?php

// database/factories/UserFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->unique()->safeEmail;

        return [
            'first_name' => Crypt::encrypt($firstName),
            'last_name' => Crypt::encrypt($lastName),
            'email' => Crypt::encrypt($email),
            'email_hash' => hash('sha256', $email),
            'password' => bcrypt('password'),
            'account_type' => $this->faker->randomElement([User::ROLE_STUDENT, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]),
            'program_id' => null,
            'status' => 'active',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'permissions' => null,
        ];
    }

    public function superAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_type' => User::ROLE_SUPER_ADMIN,
                'permissions' => implode(', ', $this->superAdminPermissions()),
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_type' => User::ROLE_ADMIN,
                'permissions' => implode(', ', $this->adminPermissions()),
            ];
        });
    }

    public function student()
    {
        return $this->state(function (array $attributes) {
            // Decrypt the first and last names to generate the email
            $firstName = Crypt::decrypt($attributes['first_name']);
            $lastName = Crypt::decrypt($attributes['last_name']);

            // Create student email format: first initial + last name + random 3 digits @usep.edu.ph
            $email = strtolower($firstName[0]).strtolower(preg_replace('/[^a-zA-Z]/', '', $lastName)).rand(100, 999).'@usep.edu.ph';

            return [
                'account_type' => User::ROLE_STUDENT,
                'permissions' => implode(', ', $this->studentPermissions()),
                'email' => Crypt::encrypt($email),
                'email_hash' => hash('sha256', $email),
                'password' => Hash::make('!2Qwerty'),
                'program_id' => $attributes['program_id'] ?? null,
                'status' => 'active',
                'email_verified_at' => now(),
            ];
        });
    }

    protected function superAdminPermissions(): array
    {
        return ['view-dashboard', 'view-thesis-submissions', 'view-forms-submissions', 'acc-rej-thesis-submissions', 'acc-rej-forms-submissions', 'view-inventory', 'add-inventory', 'edit-inventory', 'export-inventory', 'import-inventory', 'view-accounts', 'edit-permissions', 'add-admin', 'view-logs', 'view-backup', 'download-backup', 'allow-restore', 'modify-programs-list', 'modify-advisers-list'];
    }

    protected function adminPermissions(): array
    {
        return ['view-dashboard', 'view-thesis-submissions', 'view-forms-submissions', 'acc-rej-thesis-submissions', 'acc-rej-forms-submissions', 'view-inventory', 'add-inventory', 'edit-inventory', 'export-inventory'];
    }

    protected function studentPermissions(): array
    {
        return ['view-dashboard', 'submit-thesis', 'view-own-submissions'];
    }
}
