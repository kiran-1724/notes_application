<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                <p class="text-slate-600 mt-1">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your notes.</p>
            </div>
            <a href="{{ route('notes.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create New Note
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Overview - Balanced Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Notes -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Total Notes</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $totalNotes }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ Str::plural('note', $totalNotes) }} created</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Quick Action -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200 text-white">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Start Writing</h3>
                        <p class="text-white text-opacity-80 text-sm mb-4">Capture your thoughts and ideas</p>
                        <a href="{{ route('notes.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-slate-800 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors duration-200">
                            Create Note
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Notes Section - Clickable Titles Only -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Recent Notes</h2>
                            <p class="text-sm text-slate-600 mt-1">Your most recently created and updated notes</p>
                        </div>
                        <a href="{{ route('notes.index') }}" 
                           class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors duration-200">
                            View all notes
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    @if($recentNotes->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentNotes as $note)
                                <div class="group p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('notes.show', $note) }}" class="block">
                                                <h3 class="font-medium text-slate-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $note->title }}</h3>
                                                <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit($note->content, 100) }}</p>
                                                <div class="flex items-center space-x-4 mt-2 text-xs text-slate-500">
                                                    <span>{{ $note->created_at->format('M d, Y') }}</span>
                                                    @if($note->updated_at != $note->created_at)
                                                        <span>Updated {{ $note->updated_at->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900 mb-2">No notes yet</h3>
                            <p class="text-slate-600 mb-6">Get started by creating your first note. Capture ideas, thoughts, and important information.</p>
                            <a href="{{ route('notes.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-slate-800 text-white rounded-lg font-medium hover:bg-slate-700 transition-colors duration-200 shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Your First Note
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>