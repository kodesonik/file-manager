<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class ProjectController extends VoyagerBaseController
{
    public function store(Request $request)
    {
        // Generate API key before storing
        $request->merge([
            'api_key' => Str::random(32)
        ]);

        // Call the parent store method
        return parent::store($request);
    }
} 