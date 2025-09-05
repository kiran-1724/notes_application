<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <a href="{{ route('notes.index') }}" 
                   class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    All Notes
                </a>
                <div class="w-px h-6 bg-gray-300"></div>
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Note Details</h1>
                    <p class="text-sm text-slate-600 mt-1">View your note</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
            
                <button x-data=""
                        x-on:click.prevent="deleteUrl = '{{ route('notes.destroy', $note) }}'; $dispatch('open-modal', 'confirm-note-deletion')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </x-slot>

     <div class="py-8" x-data="{ deleteUrl: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-8">
                    <!-- Note Title -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-slate-900 leading-tight mb-4 break-words">
                            {{ $note->title }}
                        </h1>
                        
                        <!-- Note Metadata -->
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-slate-600 bg-gray-50 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Created {{ $note->created_at->format('M d, Y') }}</span>
                            </div>
                            @if ($note->created_at->ne($note->updated_at))
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Updated {{ $note->updated_at->diffForHumans() }}</span>
                                </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>{{ str_word_count(strip_tags($note->content)) }} words</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Note Content (with rich text rendering) -->
                    <div class="prose prose-slate max-w-none text-slate-800 text-base leading-relaxed">
                        {{-- IMPORTANT: Use {!! !!} to render HTML from Trix editor --}}
                        {!! $note->content !!}
                    </div>
                </div>

                <!-- Single Bottom Action Bar -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('notes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Back to All Notes
                        </a>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('notes.edit', $note) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 border border-amber-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Note
                            </a>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Use the reusable delete modal component with the note's title -->
        <x-delete-note-modal :note-title="$note->title" />
    </div>

    <style>
    .prose {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        line-height: 1.7;
    }
    .prose h1, .prose h2, .prose h3 {
        color: #1e293b;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose h1 { font-size: 1.875rem; }
    .prose h2 { font-size: 1.5rem; }
    .prose h3 { font-size: 1.25rem; }
    .prose strong { 
        font-weight: 600; 
        color: #0f172a;
    }
    .prose em { 
        font-style: italic; 
        color: #475569;
    }
    .prose ul, .prose ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }
    .prose li {
        margin: 0.5rem 0;
    }
    .prose blockquote {
        border-left: 4px solid #e2e8f0;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #64748b;
        background-color: #f8fafc;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    </style>
</x-app-layout>