<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Recent Projects -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Recent Projects</h2>
        </div>
        <div class="p-4">
            <div class="space-y-4">
                @forelse($stats['recent_projects'] as $project)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $project->name }}</p>
                            <p class="text-sm text-gray-500">
                                Created: {{ $project->created_at->diffForHumans() }}
                                <br>
                                Users: {{ $project->users->count() }}
                            </p>
                        </div>
                        <a href="{{ route('voyager.projects.edit', $project->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900">
                            View Details
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500">No recent projects</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Recent Users</h2>
        </div>
        <div class="p-4">
            <div class="space-y-4">
                @forelse($stats['recent_users'] as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ $user->avatar ? Voyager::image($user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-10 h-10 rounded-full">
                            <div class="ml-3">
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('voyager.users.edit', $user->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900">
                            View Profile
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500">No recent users</p>
                @endforelse
            </div>
        </div>
    </div>
</div> 