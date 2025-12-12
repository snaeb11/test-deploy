<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_STUDENT = 'student';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_FACULTY = 'faculty';

    protected $fillable = ['first_name', 'last_name', 'email', 'email_hash', 'password', 'account_type', 'program_id', 'status', 'verification_code', 'verification_code_expires_at', 'deactivated_at', 'scheduled_for_deletion', 'permissions', 'email_verified_at'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
            'deactivated_at' => 'datetime',
            'scheduled_for_deletion' => 'datetime',
        ];
    }

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'submitted_by');
    }

    public function reviewedSubmissions()
    {
        return $this->hasMany(Submission::class, 'reviewed_by');
    }

    public function getVerificationCodeAttribute($value)
    {
        return $value;
    }

    public function getDecryptedFirstNameAttribute()
    {
        try {
            return Crypt::decrypt($this->getRawOriginal('first_name'));
        } catch (DecryptException $e) {
            return null;
        }
    }

    public function getDecryptedLastNameAttribute()
    {
        try {
            return Crypt::decrypt($this->getRawOriginal('last_name'));
        } catch (DecryptException $e) {
            return null;
        }
    }

    public function getFullNameAttribute()
    {
        return "{$this->decrypted_first_name} {$this->decrypted_last_name}";
    }

    public function getEmailForVerification()
    {
        try {
            return Crypt::decrypt($this->getRawOriginal('email'));
        } catch (DecryptException $e) {
            return null;
        }
    }

    public static function findByDecryptedEmail(string $email): ?self
    {
        return self::all()->first(function ($user) use ($email) {
            try {
                return Str::lower(Crypt::decrypt($user->getRawOriginal('email'))) === Str::lower($email);
            } catch (DecryptException $e) {
                return false;
            }
        });
    }

    public function getEmailAttribute($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return null;
        }
    }

    public function hasPermission(string $permission): bool
    {
        $permissionString = $this->permissions;

        if (! $permissionString) {
            return false;
        }

        $permissionArray = array_map('trim', explode(', ', $permissionString));

        return in_array($permission, $permissionArray);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
