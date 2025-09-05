<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('notes.show', $note) }}" 
               class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Note
            </a>
            <div class="w-px h-6 bg-gray-300"></div>
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Edit Note</h1>
                <p class="text-sm text-slate-600 mt-1">Make changes to your note</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <form method="POST" action="{{ route('notes.update', $note) }}" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')

                    <!-- Title Input -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-2">
                            Note Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $note->title) }}"
                               maxlength="100"
                               placeholder="Enter your note title..."
                               class="block w-full px-4 py-3 text-lg border border-gray-300 rounded-lg shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-colors duration-200 @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                               required 
                               autofocus>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Maximum 100 characters</p>
                    </div>

                    <!-- Advanced Content Editor -->
                    <div class="mb-8">
                        <label for="content" class="block text-sm font-medium text-slate-700 mb-2">
                            Content <span class="text-red-500">*</span>
                        </label>
                        <x-advanced-editor 
                            name="content" 
                            :value="old('content', $note->content)" 
                            placeholder="Edit your note content..."
                            required />
                        @error('content')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Note Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Created {{ $note->created_at->format('M d, Y \a\t g:i A') }}</span>
                            @if($note->updated_at != $note->created_at)
                                <span class="mx-2">•</span>
                                <span>Last updated {{ $note->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('notes.show', $note) }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-2.5 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>