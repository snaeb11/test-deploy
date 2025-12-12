<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasFactory;

    // Account Type Constants
    public const ACCOUNT_SUPER_ADMIN = 'super_admin';

    public const ACCOUNT_ADMIN = 'admin';

    public const ACCOUNT_STUDENT = 'student';

    public const ACCOUNT_FACULTY = 'faculty';

    // Action Constants (Complete set from migration)
    public const ACTION_REGISTERED = 'registered';

    public const ACTION_LOGGED_IN = 'logged_in';

    public const ACTION_LOGGED_OUT = 'logged_out';

    public const ACTION_EMAIL_VERIFIED = 'email_verified';

    public const ACTION_PASSWORD_CHANGED = 'password_changed';

    public const ACTION_PASSWORD_RESET_REQUESTED = 'password_reset_requested';

    public const ACTION_PASSWORD_RESET_SUCCESSFUL = 'password_reset_successful';

    public const ACTION_ACCOUNT_DEACTIVATED = 'account_deactivated';

    public const ACTION_ACCOUNT_REACTIVATED = 'account_reactivated';

    public const ACTION_PROFILE_UPDATED = 'profile_updated';

    public const ACTION_PROGRAM_CHANGED = 'program_changed';

    public const ACTION_THESIS_SUBMITTED = 'thesis_submitted';

    public const ACTION_FORM_SUBMITTED = 'form_submitted';

    public const ACTION_FORM_DELETED = 'form_deleted';

    public const ACTION_THESIS_UPDATED = 'thesis_updated';

    public const ACTION_THESIS_DELETED = 'thesis_deleted';

    public const ACTION_THESIS_APPROVED = 'thesis_approved';

    public const ACTION_THESIS_DECLINED = 'thesis_declined';

    public const ACTION_FORM_APPROVED = 'form_approved';

    public const ACTION_FORM_REJECTED = 'form_rejected';

    public const ACTION_FORM_FORWARDED = 'form_forwarded';

    public const ACTION_REMARKS_ADDED = 'remarks_added';

    public const ACTION_INVENTORY_ADDED = 'inventory_added';

    public const ACTION_INVENTORY_IMPORTED = 'inventory_imported';

    public const ACTION_INVENTORY_EXPORTED = 'inventory_exported';

    public const ACTION_THESIS_ARCHIVED = 'thesis_archived';

    public const ACTION_USER_CREATED = 'user_created';

    public const ACTION_ADMIN_ADDED = 'admin_added';

    public const ACTION_PERMISSIONS_UPDATED = 'permissions_updated';

    public const ACTION_BACKUP_CREATED = 'backup_created';

    public const ACTION_SYSTEM_RESTORED = 'system_restored';

    public const ACTION_BACKUP_RESTORED = 'backup_restored';

    public const ACTION_SYSTEM_RESET = 'system_reset';

    // Program Management Actions
    public const ACTION_PROGRAM_CREATED = 'program_created';

    public const ACTION_PROGRAM_UPDATED = 'program_updated';

    public const ACTION_PROGRAM_DELETED = 'program_deleted';

    // Adviser Management Actions
    public const ACTION_ADVISER_CREATED = 'adviser_created';

    public const ACTION_ADVISER_UPDATED = 'adviser_updated';

    public const ACTION_ADVISER_DELETED = 'adviser_deleted';

    public const ACTION_DOWNLOADABLE_FORM_CREATED = 'downloadable_form_created';

    public const ACTION_DOWNLOADABLE_FORM_UPDATED = 'downloadable_form_updated';

    public const ACTION_DOWNLOADABLE_FORM_DELETED = 'downloadable_form_deleted';

    const CREATED_AT = 'performed_at';

    const UPDATED_AT = null;

    protected $table = 'user_activity_logs';

    protected $fillable = ['user_id', 'account_type', 'program_id', 'action', 'target_table', 'target_id', 'performed_at', 'metadata'];

    protected $casts = [
        'performed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function program()
    {
        return $this->belongsTo(Program::class)->withDefault();
    }

    public function target()
    {
        return $this->morphTo('target', 'target_table', 'target_id');
    }

    // Scopes
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    public function scopeForTargetType($query, $table)
    {
        return $query->where('target_table', $table);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            self::ACTION_REGISTERED => 'Registered',
            self::ACTION_LOGGED_IN => 'Logged In',
            self::ACTION_LOGGED_OUT => 'Logged Out',
            self::ACTION_EMAIL_VERIFIED => 'Email Verified',
            self::ACTION_PASSWORD_CHANGED => 'Password Changed',
            self::ACTION_PASSWORD_RESET_REQUESTED => 'Password Reset Requested',
            self::ACTION_PASSWORD_RESET_SUCCESSFUL => 'Password Reset Successful',
            self::ACTION_ACCOUNT_DEACTIVATED => 'Account Deactivated',
            self::ACTION_ACCOUNT_REACTIVATED => 'Account Reactivated',
            self::ACTION_PROFILE_UPDATED => 'Profile Updated',
            self::ACTION_PROGRAM_CHANGED => 'Program Changed',
            self::ACTION_THESIS_SUBMITTED => 'Thesis Submitted',
            self::ACTION_FORM_SUBMITTED => 'Form Submitted',
            self::ACTION_FORM_DELETED => 'Form Deleted',
            self::ACTION_THESIS_UPDATED => 'Thesis Updated',
            self::ACTION_THESIS_DELETED => 'Thesis Deleted',
            self::ACTION_THESIS_APPROVED => 'Thesis Approved',
            self::ACTION_THESIS_DECLINED => 'Thesis Declined',
            self::ACTION_FORM_APPROVED => 'Form Approved',
            self::ACTION_FORM_REJECTED => 'Form Rejected',
            self::ACTION_FORM_FORWARDED => 'Form Forwarded',
            self::ACTION_REMARKS_ADDED => 'Remarks Added',
            self::ACTION_INVENTORY_ADDED => 'Inventory Item Added',
            self::ACTION_INVENTORY_IMPORTED => 'Inventory Imported',
            self::ACTION_INVENTORY_EXPORTED => 'Inventory Exported',
            self::ACTION_THESIS_ARCHIVED => 'Thesis Archived',
            self::ACTION_USER_CREATED => $this->target_table === 'users' && ($this->metadata['account_type'] ?? null) === self::ACCOUNT_ADMIN ? 'Admin Added' : 'User Created',
            self::ACTION_PERMISSIONS_UPDATED => 'Permissions Updated',
            self::ACTION_BACKUP_CREATED => 'Database Backup Created',
            self::ACTION_SYSTEM_RESTORED => 'Database Restored',
            self::ACTION_BACKUP_RESTORED => 'Database Restored',
            self::ACTION_SYSTEM_RESET => 'Database Reset',
            self::ACTION_PROGRAM_CREATED => 'Program Created',
            self::ACTION_PROGRAM_UPDATED => 'Program Updated',
            self::ACTION_PROGRAM_DELETED => 'Program Deleted',
            self::ACTION_ADVISER_CREATED => 'Adviser Created',
            self::ACTION_ADVISER_UPDATED => 'Adviser Updated',
            self::ACTION_ADVISER_DELETED => 'Adviser Deleted',
            self::ACTION_DOWNLOADABLE_FORM_CREATED => 'Downloadable Form Created',
            self::ACTION_DOWNLOADABLE_FORM_UPDATED => 'Downloadable Form Updated',
            self::ACTION_DOWNLOADABLE_FORM_DELETED => 'Downloadable Form Deleted',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Improved log method with better target handling
     */
    public static function log(User $user, string $action, Model|string|null $target = null, ?int $programId = null, ?array $metadata = null): self
    {
        $targetTable = null;
        $targetId = null;

        if ($target instanceof Model) {
            $targetTable = $target->getTable();
            $targetId = $target->getKey();
        } elseif (is_string($target)) {
            $targetTable = $target;
        }

        return self::create([
            'user_id' => $user->id,
            'account_type' => $user->account_type,
            'program_id' => $programId ?? $user->program_id,
            'action' => $action,
            'target_table' => $targetTable,
            'target_id' => $targetId,
            'performed_at' => now(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Alternative fluent logging interface
     */
    public static function forUser(User $user): self
    {
        $log = new static();
        $log->user_id = $user->id;
        $log->account_type = $user->account_type;
        $log->program_id = $user->program_id;

        return $log;
    }

    public function withAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function withTarget(Model|string|null $target): self
    {
        if ($target instanceof Model) {
            $this->target_table = $target->getTable();
            $this->target_id = $target->getKey();
        } elseif (is_string($target)) {
            $this->target_table = $target;
        }

        return $this;
    }

    public function saveWithTimestamp(): bool
    {
        $this->performed_at = now();

        return $this->save();
    }
}
