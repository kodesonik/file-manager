<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'name',
        'folder',
        'description',
        'api_key',
    ];

    /**
     * Generate sanitized folder name from project name
     */
    private function generateFolderName(): string
    {
        // Convert to lowercase and replace spaces with hyphens
        $folder = Str::lower($this->name);
        $folder = str_replace(' ', '-', $folder);
        
        // Remove any special characters except hyphens and alphanumeric
        $folder = preg_replace('/[^a-z0-9\-]/', '', $folder);
        
        // Remove multiple consecutive hyphens
        $folder = preg_replace('/-+/', '-', $folder);
        
        // Trim hyphens from beginning and end
        return trim($folder, '-');
    }

    protected static function boot()
    {
        parent::boot();
        
        // Generate API key and folder name before creating
        static::creating(function ($project) {
            $project->api_key = Str::random(32);
            $project->folder = $project->generateFolderName();
        });

        // Create project directory after creation
        static::created(function ($project) {
            $path = storage_path('app/public/' . $project->folder);
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
} 