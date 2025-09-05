@props(['noteTitle' => null])

<x-modal name="confirm-note-deletion" focusable>
    <div class="p-6">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Delete Note</h2>
                <p class="text-sm text-slate-600 mt-1">This action cannot be undone</p>
            </div>
        </div>

        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-800">
                
                Are you sure you want to permanently delete
                @if($noteTitle)
                    "<strong>{{ $noteTitle }}</strong>"
                @else
                    this note
                @endif
                ? Once deleted, it cannot be recovered.
            </p>
        </div>

        <form method="post" :action="deleteUrl">
            @csrf
            @method('delete')
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        x-on:click="$dispatch('close')"
                        class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Keep Note
                </button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200">
                    Delete Permanently
                </button>
            </div>
        </form>
    </div>
</x-modal>