<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'degree'];

    /**
     * Get the submissions for this program.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the inventory items for this program.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the users enrolled in this program.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the advisers for this program.
     */
    public function advisers(): HasMany
    {
        return $this->hasMany(Adviser::class);
    }

    /**
     * Scope for undergraduate programs.
     */
    public function scopeUndergraduate($query)
    {
        return $query->where('degree', 'Undergraduate');
    }

    /**
     * Scope for graduate programs.
     */
    public function scopeGraduate($query)
    {
        return $query->where('degree', 'Graduate');
    }

    /**
     * Resolve a canonical program acronym for a given program name.
     * Accepts either a full descriptive name or an acronym and returns the acronym.
     */
    public static function getAcronymForName(?string $programName): string
    {
        $name = trim((string) $programName);
        if ($name === '') {
            return 'GEN';
        }

        // Mapping of full names → acronyms (provided mapping)
        $mapping = [
            'Bachelor of Elementary Education' => 'BEEd',
            'Bachelor of Science in Information Technology Major in Information Security' => 'BSIT',
            'Bachelor of Technical-Vocational Teacher Education' => 'BTVTEd',
            'Bachelor of Early Childhood Education' => 'BECEd',
            'Bachelor of Special Needs Education' => 'BSNEd',
            'Bachelor of Secondary Education Major in Mathematics' => 'BSEd-Math',
            'Bachelor of Secondary Education Major in English' => 'BSEd-Eng',
            'Bachelor of Secondary Education Major in Filipino' => 'BSEd-Fil',
            'Doctor of Education Major in Educational Management' => 'EDD',
            'Master of Education in Educational Management' => 'MEEM',
            'Master of Education in Language Teaching Major in English' => 'MEDLT',
        ];

        // Exact acronym passthrough
        $acronyms = array_values($mapping);
        if (in_array($name, $acronyms, true)) {
            return $name;
        }

        // Case-insensitive exact match on full name
        foreach ($mapping as $full => $acro) {
            if (strcasecmp($name, $full) === 0) {
                return $acro;
            }
        }

        // If parentheses exist, prefer content within as acronym
        if (preg_match('/\(([^)]+)\)/', $name, $m)) {
            $paren = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $m[1]));
            if ($paren !== '') {
                return $paren;
            }
        }

        // Fallback: build acronym from word initials
        $words = preg_split('/\s+/', $name) ?: [];
        $initials = array_map(function ($w) {
            $w = preg_replace('/[^A-Za-z0-9]/', '', $w);

            return $w !== '' ? strtoupper(substr($w, 0, 1)) : '';
        }, $words);
        $acronym = implode('', $initials);
        $acronym = $acronym !== '' ? $acronym : strtoupper(preg_replace('/\s+/', '', $name));

        return $acronym !== '' ? $acronym : 'GEN';
    }
}
