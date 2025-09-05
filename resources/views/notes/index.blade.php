<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Notes') }}
            </h2>
            <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('+ New Note') }}
            </a>
        </div>
    </x-slot>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ deleteUrl: '' }">
            
            <!-- Filter Component -->
            <x-notes.filter :filters="$filters" />

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Notes Grid or Empty State -->
            @if($notes->isEmpty())
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    <p class="font-semibold">No notes found.</p>
                    <p class="mt-1">Try adjusting your search or filters, or <a href="{{ route('notes.index') }}" class="text-indigo-600 hover:text-indigo-900">clear all filters</a>.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($notes as $note)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                            <!-- Card Body -->
                            <div class="p-6 flex-grow">
                                <h3 class="font-bold text-lg text-gray-800">{{ $note->title }}</h3>
                                <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ Str::limit($note->content, 150) }}</p>
                            </div>
                            <!-- Card Footer -->
                            <div class="p-4 bg-gray-50 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        {{ $note->updated_at->diffForHumans() }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('notes.show', $note) }}">
                                            <x-secondary-button class="!px-2 !py-1 text-xs">{{ __('View') }}</x-secondary-button>
                                        </a>
                                        <a href="{{ route('notes.edit', $note) }}">
                                            <x-secondary-button class="!px-2 !py-1 text-xs">{{ __('Edit') }}</x-secondary-button>
                                        </a>
                                        <x-danger-button class="!px-2 !py-1 text-xs"
                                            x-on:click.prevent="deleteUrl = '{{ route('notes.destroy', $note) }}'; $dispatch('open-modal', 'confirm-note-deletion')">
                                            {{ __('Delete') }}
                                        </x-danger-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination Links -->
            @if($notes->hasPages())
                <div class="mt-8">
                    {{ $notes->links() }}
                </div>
            @endif

            <!-- Delete Confirmation Modal -->
            <x-modal name="confirm-note-deletion" focusable>
                <form method="post" :action="deleteUrl" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Are you sure you want to delete this note?') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Once this note is deleted, it cannot be recovered. Please confirm you would like to permanently delete this note.') }}
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
    </div>
</x-app-layout>