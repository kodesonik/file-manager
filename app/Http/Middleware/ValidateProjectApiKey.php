<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Project;
use Illuminate\Http\Request;

class ValidateProjectApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            return response()->json(['error' => 'API key is missing'], 401);
        }

        $project = Project::where('api_key', $apiKey)->first();
        
        if (!$project) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        // Add project to the request for later use
        $request->attributes->add(['project' => $project]);
        
        return $next($request);
    }
} 