@props(['filters'])

<div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <form method="GET" action="{{ route('notes.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <!-- Search Input -->
                <div>
                    <x-input-label for="search" :value="__('Search')" />
                    <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" 
                                  :value="$filters['search'] ?? ''" placeholder="Search by title or content..." />
                </div>

                <!-- Sort Dropdown -->
                <div>
                    <x-input-label for="sort" :value="__('Sort By')" />
                    <select name="sort" id="sort" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="newest" @if (($filters['sort'] ?? 'newest') == 'newest') selected @endif>Newest First</option>
                        <option value="oldest" @if (($filters['sort'] ?? '') == 'oldest') selected @endif>Oldest First</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-2">
                    <x-primary-button>
                        {{ __('Filter') }}
                    </x-primary-button>
                    <a href="{{ route('notes.index') }}">
                        <x-secondary-button type="button">
                            {{ __('Clear') }}
                        </x-secondary-button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>