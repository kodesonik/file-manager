@extends('voyager::master')

@section('content')
    <div class="page-content">
        <div class="analytics-container">
            <!-- Project Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Project Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Projects:</span>
                            <span class="font-semibold">{{ $stats['projects']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>New Today:</span>
                            <span class="font-semibold">{{ $stats['projects']['today'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>This Week:</span>
                            <span class="font-semibold">{{ $stats['projects']['week'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">User Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Users:</span>
                            <span class="font-semibold">{{ $stats['users']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Active Users:</span>
                            <span class="font-semibold">{{ $stats['users']['active'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Administrators:</span>
                            <span class="font-semibold">{{ $stats['users']['admins'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Storage Usage</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Storage:</span>
                            <span class="font-semibold">{{ $stats['storage']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Active Projects:</span>
                            <span class="font-semibold">{{ $stats['project_stats']['active_projects'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Unused Projects:</span>
                            <span class="font-semibold">{{ $stats['project_stats']['unused_projects'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rest of your existing dashboard content -->
            @include('admin.dashboard.recent-activity')
        </div>
    </div>
@stop

@section('css')
    @parent
    <style>
        .analytics-container {
            padding: 2rem;
        }
        .grid {
            display: grid;
        }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .bg-white { background-color: white; }
        .rounded-lg { border-radius: 0.5rem; }
        .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
        .p-6 { padding: 1.5rem; }
        .p-4 { padding: 1rem; }
        .border-b { border-bottom: 1px solid #e5e7eb; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .font-semibold { font-weight: 600; }
        .text-gray-500 { color: #6b7280; }
        .text-indigo-600 { color: #4f46e5; }
        .hover\:text-indigo-900:hover { color: #312e81; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .space-y-3 > * + * { margin-top: 0.75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    </style>
@stop 
@stop 