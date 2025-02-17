<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $documentation['name'] }} - Documentation</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
        <script>hljs.highlightAll();</script>
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $documentation['name'] }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Version {{ $documentation['version'] }}</p>
                </div>
            </header>

            <!-- Main content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Endpoints -->
                <div class="space-y-8">
                    @foreach ($documentation['endpoints'] as $endpoint)
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <!-- Endpoint header -->
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $endpoint['method'] === 'POST' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $endpoint['method'] }}
                                    </span>
                                    <h3 class="ml-2 text-lg leading-6 font-medium text-gray-900">{{ $endpoint['path'] }}</h3>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">{{ $endpoint['description'] }}</p>
                            </div>

                            <!-- Endpoint details -->
                            <div class="px-4 py-5 sm:p-6">
                                <!-- Parameters -->
                                @if(isset($endpoint['parameters']))
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Parameters</h4>
                                        <div class="bg-gray-50 rounded-md p-4">
                                            <dl class="divide-y divide-gray-200">
                                                @foreach ($endpoint['parameters'] as $param => $desc)
                                                    <div class="py-2">
                                                        <dt class="text-sm font-medium text-gray-500">{{ $param }}</dt>
                                                        <dd class="mt-1 text-sm text-gray-900">{{ $desc }}</dd>
                                                    </div>
                                                @endforeach
                                            </dl>
                                        </div>
                                    </div>
                                @endif

                                <!-- Headers -->
                                @if(isset($endpoint['headers']))
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Headers</h4>
                                        <div class="bg-gray-50 rounded-md p-4">
                                            <dl class="divide-y divide-gray-200">
                                                @foreach ($endpoint['headers'] as $header => $desc)
                                                    <div class="py-2">
                                                        <dt class="text-sm font-medium text-gray-500">{{ $header }}</dt>
                                                        <dd class="mt-1 text-sm text-gray-900">{{ $desc }}</dd>
                                                    </div>
                                                @endforeach
                                            </dl>
                                        </div>
                                    </div>
                                @endif

                                <!-- Examples -->
                                @if(isset($endpoint['example']))
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Example</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <p class="text-sm text-gray-500 mb-1">Request:</p>
                                                <pre><code class="language-bash">{{ $endpoint['example']['curl'] }}</code></pre>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500 mb-1">Response:</p>
                                                <pre><code class="language-json">{{ json_encode($endpoint['example']['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Multiple Examples -->
                                @if(isset($endpoint['examples']))
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Examples</h4>
                                        <div class="space-y-6">
                                            @foreach ($endpoint['examples'] as $example)
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 mb-2">{{ $example['description'] }}</p>
                                                    <pre><code class="language-bash">{{ $example['curl'] }}</code></pre>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </body>
</html>
