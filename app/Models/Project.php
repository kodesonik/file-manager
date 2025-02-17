<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'api_key',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Generate API key before creating
        static::creating(function ($project) {
            $project->api_key = Str::random(32);
        });

        // Create project directory after creation
        static::created(function ($project) {
            $path = storage_path('app/public/' . $project->name);
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