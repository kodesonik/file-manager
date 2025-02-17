<?php

namespace App\Http\Controllers\Admin;

use TCG\Voyager\Http\Controllers\VoyagerController;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends VoyagerController
{
    public function index()
    {
        // Get statistics
        $stats = [
            'projects' => [
                'total' => Project::count(),
                'today' => Project::whereDate('created_at', today())->count(),
                'week' => Project::whereDate('created_at', '>=', now()->subWeek())->count(),
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::where('role_id', 2)->count(), // Regular users
                'admins' => User::where('role_id', 1)->count(), // Admins
            ],
            'storage' => [
                'total' => $this->getStorageUsage(),
                'by_project' => $this->getStorageByProject(),
            ],
            'recent_projects' => Project::with('users')
                                      ->latest()
                                      ->take(5)
                                      ->get(),
            'recent_users' => User::latest()
                                 ->take(5)
                                 ->get(),
            'project_stats' => $this->getProjectStats(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function getStorageUsage()
    {
        $publicPath = storage_path('app/public');
        $totalSize = 0;

        if (is_dir($publicPath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicPath)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                }
            }
        }

        return $this->formatBytes($totalSize);
    }

    private function getStorageByProject()
    {
        $projects = Project::all();
        $storage = [];

        foreach ($projects as $project) {
            $path = storage_path('app/public/' . $project->name);
            $size = 0;

            if (is_dir($path)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path)
                );

                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $size += $file->getSize();
                    }
                }
            }

            $storage[$project->name] = $this->formatBytes($size);
        }

        return $storage;
    }

    private function getProjectStats()
    {
        return [
            'uploads_today' => DB::table('project_user')
                               ->whereDate('created_at', today())
                               ->count(),
            'active_projects' => Project::has('users')
                                      ->count(),
            'unused_projects' => Project::doesntHave('users')
                                      ->count(),
        ];
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
} 