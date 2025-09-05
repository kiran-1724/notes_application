<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Welcome & Stats Card -->
                <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="text-2xl font-semibold text-gray-800">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="mt-2 text-gray-600">
                        You currently have 
                        <span class="font-bold text-indigo-600">{{ $totalNotes }}</span> 
                        {{ Str::plural('note', $totalNotes) }}. Keep up the great work!
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('notes.create') }}">
                            <x-primary-button>
                                {{ __('+ Create a New Note') }}
                            </x-primary-button>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-3">Recent Activity</h3>
                        
                        @forelse ($recentNotes as $note)
                            <div class="py-4 border-b last:border-b-0">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <a href="{{ route('notes.show', $note) }}" class="font-bold text-gray-800 hover:text-indigo-600">
                                            {{ $note->title }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Last updated: {{ $note->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('notes.show', $note) }}">
                                            <x-secondary-button class="!px-3 !py-1 text-xs">{{ __('View') }}</x-secondary-button>
                                        </a>
                                        <a href="{{ route('notes.edit', $note) }}">
                                            <x-secondary-button class="!px-3 !py-1 text-xs">{{ __('Edit') }}</x-secondary-button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-4 text-center text-gray-500">
                                <p>You haven't updated any notes recently.</p>
                                <p class="mt-1">
                                    <a href="{{ route('notes.index') }}" class="text-indigo-600 hover:underline">View all your notes</a> to get started.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>