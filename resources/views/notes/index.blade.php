<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">My Notes</h1>
                <p class="text-slate-600 mt-1">{{ $notes->total() }} {{ Str::plural('note', $notes->total()) }} found</p>
            </div>
            <a href="{{ route('notes.create') }}" 
               class="inline-flex items-center px-6 py-2.5 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                New Note
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ deleteUrl: '', searchTimeout: null }">
            
            <!-- Enhanced Filter Component -->
            <div class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6">
                    <form method="GET" action="{{ route('notes.index') }}" x-data="{ 
                        hasFilters: {{ json_encode(!empty($filters['search']) || (!empty($filters['sort']) && $filters['sort'] !== 'newest')) }},
                        searchValue: '{{ $filters['search'] ?? '' }}',
                        submitSearch() {
                            clearTimeout(this.searchTimeout);
                            this.searchTimeout = setTimeout(() => {
                                $el.submit();
                            }, 500);
                        }
                    }" id="searchForm">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Search Input with Icon -->
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="search" 
                                           x-model="searchValue"
                                           @input="submitSearch()"
                                           placeholder="Search notes by title or content..."
                                           class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-colors duration-200">
                                </div>
                            </div>

                            <!-- Sort and Filter Controls -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                <!-- Sort Dropdown -->
                                <div class="sm:w-48">
                                    <select name="sort" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-colors duration-200"
                                            onchange="document.getElementById('searchForm').submit()">
                                        <option value="newest" {{ ($filters['sort'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Latest First</option>
                                        <option value="oldest" {{ ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    </select>
                                </div>

                                <!-- Clear Filters Button -->
                                <div x-show="hasFilters" x-transition class="sm:w-auto">
                                    <a href="{{ route('notes.index') }}" 
                                       class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Clear Filters
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        @if(!empty($filters['search']) || (!empty($filters['sort']) && $filters['sort'] !== 'newest'))
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-sm text-gray-600">Active filters:</span>
                                @if(!empty($filters['search']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ $filters['search'] }}"
                                        <a href="{{ route('notes.index', array_merge($filters, ['search' => ''])) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </a>
                                    </span>
                                @endif
                                @if(!empty($filters['sort']) && $filters['sort'] !== 'newest')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Sort: {{ ucfirst($filters['sort']) }} First
                                    </span>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Notes Grid or Empty State -->
            @if($notes->isEmpty())
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        @if(!empty($filters['search']))
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        @else
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">
                        @if(!empty($filters['search']))
                            No matching notes found
                        @else
                            No notes yet
                        @endif
                    </h3>
                    <p class="text-slate-600 mb-6 max-w-md mx-auto">
                        @if(!empty($filters['search']))
                            We couldn't find any notes matching "{{ $filters['search'] }}". Try adjusting your search terms.
                        @else
                            Start capturing your thoughts and ideas by creating your first note.
                        @endif
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        @if(!empty($filters['search']))
                            <a href="{{ route('notes.index') }}" 
                               class="inline-flex items-center px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Clear Search
                            </a>
                        @endif
                        <a href="{{ route('notes.create') }}" 
                           class="inline-flex items-center px-6 py-2.5 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Your First Note
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($notes as $note)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group overflow-hidden">
                            <!-- Clickable Card Body -->
                            <a href="{{ route('notes.show', $note) }}" class="p-6 flex-grow block hover:bg-gray-50 transition-colors duration-200">
                                <div class="mb-3">
                                    <h3 class="font-semibold text-lg text-slate-900 group-hover:text-slate-700 transition-colors duration-200 line-clamp-2">
                                        {{ $note->title }}
                                    </h3>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed line-clamp-4">
                                    {{ Str::limit($note->content, 140) }}
                                </p>
                            </a>
                            
                            <!-- Card Footer with Actions -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-slate-500">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $note->created_at->format('M d, Y') }}</span>
                                        </div>
                                        @if($note->updated_at != $note->created_at)
                                            <div class="flex items-center space-x-1 mt-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Updated {{ $note->updated_at->diffForHumans() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <a href="{{ route('notes.edit', $note) }}" 
                                           class="px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-200 border border-amber-200"
                                           onclick="event.stopPropagation()"
                                           title="Edit note">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <button x-on:click.stop="deleteUrl = '{{ route('notes.destroy', $note) }}'; $dispatch('open-modal', 'confirm-note-deletion')"
                                                class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200 border border-red-200"
                                                title="Delete note">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Professional Pagination -->
            @if($notes->hasPages())
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($notes->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $notes->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                    Previous
                                </a>
                            @endif

                            @if ($notes->hasMorePages())
                                <a href="{{ $notes->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                    Next
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                    Next
                                </span>
                            @endif
                        </div>

                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 leading-5">
                                    Showing
                                    <span class="font-medium">{{ $notes->firstItem() ?? 0 }}</span>
                                    to
                                    <span class="font-medium">{{ $notes->lastItem() ?? 0 }}</span>
                                    of
                                    <span class="font-medium">{{ $notes->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    {{-- Previous Page Link --}}
                                    @if ($notes->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $notes->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 transition ease-in-out duration-150">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($notes->getUrlRange(1, $notes->lastPage()) as $page => $url)
                                        @if ($page == $notes->currentPage())
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-slate-800 text-sm font-medium text-white cursor-default">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 transition ease-in-out duration-150">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($notes->hasMorePages())
                                        <a href="{{ $notes->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 transition ease-in-out duration-150">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Use the reusable delete modal component -->
            <x-delete-note-modal />

        </div>
    </div>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
</x-app-layout>