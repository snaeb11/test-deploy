<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = ['submission_id', 'title', 'authors', 'adviser', 'abstract', 'program_id', 'manuscript_path', 'manuscript_filename', 'manuscript_size', 'manuscript_mime', 'academic_year', 'inventory_number', 'archived_by', 'archived_at'];

    protected $casts = [
        'manuscript_size' => 'integer',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function archivist()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Scopes
     */
    public function scopeForProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('archived_at', '>=', now()->subDays($days));
    }

    /**
     * Accessors
     */
    public function getFileUrlAttribute()
    {
        return Storage::disk('archive')->url($this->archived_path);
    }

    public function getFileSizeInMbAttribute()
    {
        return round($this->file_size / 1048576, 2); // Convert bytes to MB
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->title} ({$this->inventory_number})";
    }

    /**
     * Helper Methods
     */
    public function verifyFileIntegrity(): bool
    {
        if (! Storage::disk('archive')->exists($this->archived_path)) {
            return false;
        }

        $currentHash = hash_file('sha256', Storage::disk('archive')->path($this->archived_path));

        return hash_equals($this->file_hash, $currentHash);
    }

    public static function generateInventoryNumber(Program $program): string
    {
        $year = date('Y');
        $lastSequence = self::where('academic_year', $year)->where('program_id', $program->id)->count();

        return sprintf('%s-%s-%04d', $program->short_code ?? substr($program->name, 0, 4), $year, $lastSequence + 1);
    }

    public function getSubmittedByNameAttribute()
    {
        return optional($this->submission)->submitter->full_name ?? '—';
    }

    public function getReviewedByNameAttribute()
    {
        if ($this->submission_id) {
            return optional($this->submission->reviewer)->full_name ?? '—';
        }

        return optional($this->archivist)->full_name ?? '—';
    }
}
