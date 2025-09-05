<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Back Link -->
            <a href="{{ route('notes.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('All Notes') }}
            </a>
            
            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('notes.edit', $note) }}">
                    <x-secondary-button>{{ __('Edit') }}</x-secondary-button>
                </a>
                <x-danger-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-note-deletion')">
                    {{ __('Delete') }}
                </x-danger-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <!-- Note Title -->
                    <h1 class="text-3xl font-bold text-gray-900 break-words">
                        {{ $note->title }}
                    </h1>
                    
                    <!-- Timestamps -->
                    <div class="mt-2 text-sm text-gray-500">
                        <span>Created: {{ $note->created_at->format('d M Y, h:i A') }}</span>
                        @if ($note->created_at->ne($note->updated_at))
                            <span class="mx-2">|</span>
                            <span>Updated: {{ $note->updated_at->format('d M Y, h:i A') }}</span>
                        @endif
                    </div>
                    
                    <!-- Divider -->
                    <hr class="my-6">
                    
                    <!-- Note Content -->
                    <div class="prose max-w-none text-gray-800 text-lg whitespace-pre-wrap break-words">
                        {{ $note->content }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal (for this page) -->
        <x-modal name="confirm-note-deletion" focusable>
            <form method="post" action="{{ route('notes.destroy', $note) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this note?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once this note is deleted, it cannot be recovered.') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Delete Note') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>