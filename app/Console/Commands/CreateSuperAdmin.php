<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:create-super-admin
                            {--email= : The admin email address (must be @usep.edu.ph)}
                            {--first-name= : First name}
                            {--last-name= : Last name}
                            {--password= : Temporary password (min 8 chars)}
                            {--force : Skip confirmation prompts}';

    protected $description = 'Create a super admin user with all permissions';

    public function handle(): int
    {
        try {
            if (! $this->shouldProceedWithCreation()) {
                return 1;
            }

            $data = $this->getValidatedInputData();
            $user = $this->createSuperAdmin($data);

            $this->outputSuperAdminDetails($user, $data['password']);

            return 0;
        } catch (ValidationException $e) {
            $this->outputValidationErrors($e);

            return 1;
        } catch (\Exception $e) {
            $this->error('Error creating super admin: '.$e->getMessage());

            return 1;
        }
    }

    protected function shouldProceedWithCreation(): bool
    {
        if (User::where('account_type', 'super_admin')->exists()) {
            if ($this->option('force')) {
                return true;
            }

            if ($this->input->isInteractive() && ! $this->confirm('A super admin already exists. Create another?')) {
                $this->warn('Super admin creation cancelled.');

                return false;
            }
        }

        return true;
    }

    protected function getValidatedInputData(): array
    {
        $useDefaults = ! $this->input->isInteractive();

        $data = [
            'email' => $this->option('email') ?? ($useDefaults ? 'superadmin@usep.edu.ph' : $this->askValid('Email address (must end with @usep.edu.ph)', 'email', $this->getEmailRules())),
            'first_name' => $this->option('first-name') ?? ($useDefaults ? 'System' : $this->askValid('First name', 'first_name', ['required', 'string', 'max:50'])),
            'last_name' => $this->option('last-name') ?? ($useDefaults ? 'Admin' : $this->askValid('Last name', 'last_name', ['required', 'string', 'max:50'])),
        ];

        $data['password'] = $this->getValidatedPassword($useDefaults);

        return $data;
    }

    protected function getEmailRules(): array
    {
        return ['required', 'email', 'ends_with:@usep.edu.ph', 'unique:users,email'];
    }

    protected function getPasswordRules(): array
    {
        return ['required', Password::min(8)->mixedCase()->numbers()->symbols()];
    }

    protected function getValidatedPassword(bool $useDefaults): string
    {
        if ($this->option('password')) {
            $validator = Validator::make(['password' => $this->option('password')], ['password' => $this->getPasswordRules()]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            return $this->option('password');
        }

        if ($useDefaults) {
            return $this->generateStrongPassword();
        }

        $password = $this->askValid('Password (min 8 chars with mixed case, numbers & symbols)', 'password', $this->getPasswordRules());

        $confirmPassword = $this->ask('Confirm Password');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match!');

            return $this->getValidatedPassword($useDefaults);
        }

        return $password;
    }

    protected function generateStrongPassword(): string
    {
        $length = 16;
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-=+;:,.?';

        do {
            $password = '';
            for ($i = 0; $i < $length; $i++) {
                $password .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (! preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{12,}$/', $password));

        return $password;
    }

    protected function askValid(string $question, string $field, array $rules)
    {
        $value = $this->ask($question);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }

    protected function validateInput(array $rules, string $field, $value): ?string
    {
        $validator = Validator::make(
            [$field => $value],
            [$field => $rules],
            [
                'email.ends_with' => 'The email must be an official @usep.edu.ph address',
                'password.uncompromised' => 'This password has appeared in a data breach. Please choose a stronger password.',
            ],
        );

        return $validator->fails() ? $validator->errors()->first($field) : null;
    }

    protected function createSuperAdmin(array $data): User
    {
        return User::create([
            'first_name' => Crypt::encrypt($data['first_name']),
            'last_name' => Crypt::encrypt($data['last_name']),
            'email' => Crypt::encrypt($data['email']),
            'email_hash' => hash('sha256', $data['email']),
            'password' => Hash::make($data['password']),
            'account_type' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'permissions' => $this->getSuperAdminPermissions(),
            'program_id' => null,
        ]);
    }

    protected function getSuperAdminPermissions(): string
    {
        return implode(', ', config('permissions.super_admin') ?? ['view-dashboard', 'view-thesis-submissions', 'view-forms-submissions', 'acc-rej-thesis-submissions', 'acc-rej-forms-submissions', 'view-inventory', 'add-inventory', 'edit-inventory', 'export-inventory', 'import-inventory', 'view-accounts', 'edit-permissions', 'add-admin', 'view-logs', 'view-backup', 'download-backup', 'allow-restore', 'modify-programs-list', 'modify-advisers-list']);
    }

    protected function outputSuperAdminDetails(User $user, string $plainPassword): void
    {
        try {
            $this->newLine();
            $this->info('╔══════════════════════════════════════════════════╗');
            $this->info('║         SUPER ADMIN CREATED SUCCESSFULLY         ║');
            $this->info('╚══════════════════════════════════════════════════╝');
            $this->newLine();

            $this->line('<fg=cyan>ID:</> '.$user->id);

            // Safely decrypt names
            try {
                $firstName = Crypt::decrypt($user->first_name);
                $lastName = Crypt::decrypt($user->last_name);
                $this->line('<fg=cyan>Name:</> '.$firstName.' '.$lastName);
            } catch (\Exception $e) {
                $this->warn('Name could not be decrypted (but account was created)');
            }

            // Safely decrypt email
            try {
                $email = Crypt::decrypt($user->email);
                $this->line('<fg=cyan>Email:</> '.$email);
            } catch (\Exception $e) {
                $this->warn('Email could not be decrypted (but was stored properly)');
                $this->line('<fg=cyan>Email Hash:</> '.$user->email_hash);
            }

            $this->line('<fg=cyan>Temporary Password:</> '.$plainPassword);

            $this->newLine();
            $this->warn('⚠️  IMPORTANT SECURITY NOTES:');
            $this->line('1. This account has full system access privileges');
            $this->line('2. Change password immediately after first login');
            $this->line('3. Store credentials securely using a password manager');
            $this->line('4. Enable two-factor authentication for this account');
            $this->line('5. Rotate credentials periodically');
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('Error displaying admin details: '.$e->getMessage());
            $this->warn('Account was created successfully, but there was an error displaying some details.');
            $this->line('You can find the user in database with ID: '.$user->id);
        }
    }

    protected function outputValidationErrors(ValidationException $e): void
    {
        $this->error('Validation errors occurred:');

        foreach ($e->errors() as $field => $errors) {
            $this->line("<fg=red>• {$field}:</>");
            foreach ($errors as $error) {
                $this->line("  - {$error}");
            }
        }
    }
}
