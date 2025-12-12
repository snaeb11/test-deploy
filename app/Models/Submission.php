<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_RESUBMITTED = 'resubmitted';

    protected $fillable = ['title', 'authors', 'adviser', 'abstract', 'manuscript_path', 'manuscript_filename', 'manuscript_size', 'manuscript_mime', 'program_id', 'submitted_by', 'resubmitted_from_id', 'submitted_at', 'status', 'reviewed_by', 'reviewed_at', 'remarks'];

    protected function casts(): array
    {
        return [
            'manuscript_size' => 'integer',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected $casts = [
        'manuscript_size' => 'integer',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'submission_id');
    }

    public function originalSubmission()
    {
        return $this->belongsTo(Submission::class, 'resubmitted_from_id');
    }

    public function resubmissions()
    {
        return $this->hasMany(Submission::class, 'resubmitted_from_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeForProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_RESUBMITTED => 'Resubmitted',
            default => 'Pending',
        };
    }

    public function getManuscriptUrlAttribute()
    {
        return $this->manuscript_path ? \Storage::url($this->manuscript_path) : null;
    }

    /**
     * Helper Methods
     */
    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function needsResubmission(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function hasManuscript(): bool
    {
        return !empty($this->manuscript_path);
    }

    public function hasBeenResubmitted(): bool
    {
        return $this->resubmissions()->exists();
    }

    public function isResubmission(): bool
    {
        return !is_null($this->resubmitted_from_id);
    }

    /**
     * Business Logic
     */
    public function markAsAccepted(User $reviewer, ?string $remarks = null): void
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'remarks' => $remarks,
        ]);
    }
}
