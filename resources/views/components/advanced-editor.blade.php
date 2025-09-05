@props(['name', 'value' => '', 'placeholder' => 'Start writing...', 'required' => false])

<div x-data="simpleEditor('{{ $value }}')" class="border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-slate-500 focus-within:border-slate-500 transition-colors duration-200">
    <!-- Toolbar -->
    <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
        <!-- Undo/Redo -->
        <div class="flex items-center gap-1">
            <button type="button" @click="undo()" :disabled="!canUndo" :class="canUndo ? 'hover:bg-gray-200' : 'opacity-50 cursor-not-allowed'" class="p-2 rounded transition-colors duration-200" title="Undo (Ctrl+Z)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
            </button>
            <button type="button" @click="redo()" :disabled="!canRedo" :class="canRedo ? 'hover:bg-gray-200' : 'opacity-50 cursor-not-allowed'" class="p-2 rounded transition-colors duration-200" title="Redo (Ctrl+Y)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/>
                </svg>
            </button>
        </div>

        <!-- Word Count -->
        <div class="flex items-center">
            <span class="text-sm text-gray-600 font-medium" x-text="wordCount + ' words'"></span>
        </div>
    </div>

    <!-- Editor Area -->
    <div class="relative">
        <textarea 
            x-ref="editor"
            :name="name"
            x-model="content"
            @input="handleInput()"
            @keydown="handleKeydown($event)"
            @select="updateSelection()"
            @mouseup="updateSelection()"
            :placeholder="placeholder"
            {{ $required ? 'required' : '' }}
            rows="12"
            class="block w-full px-4 py-3 border-0 rounded-b-lg resize-vertical focus:outline-none placeholder-gray-500"
            style="font-family: 'Inter', system-ui, -apple-system, sans-serif; line-height: 1.6;"></textarea>
    </div>
</div>

<script>
function simpleEditor(initialValue = '') {
    return {
        name: '{{ $name }}',
        placeholder: '{{ $placeholder }}',
        content: initialValue,
        wordCount: 0,
        selectionStart: 0,
        selectionEnd: 0,
        history: [],
        historyIndex: -1,
        saveTimeout: null,

        get canUndo() {
            return this.historyIndex > 0;
        },

        get canRedo() {
            return this.historyIndex < this.history.length - 1;
        },

        init() {
            this.updateWordCount();
            this.saveToHistory();
        },

        saveToHistory() {
            // Remove any history after current index
            this.history = this.history.slice(0, this.historyIndex + 1);
            // Add new state
            this.history.push({
                content: this.content,
                selectionStart: this.selectionStart,
                selectionEnd: this.selectionEnd
            });
            this.historyIndex = this.history.length - 1;
            
            // Limit history size to 50 entries
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        undo() {
            if (this.canUndo) {
                this.historyIndex--;
                const state = this.history[this.historyIndex];
                this.content = state.content;
                this.updateWordCount();
                this.$nextTick(() => {
                    this.$refs.editor.setSelectionRange(state.selectionStart, state.selectionEnd);
                    this.updateSelection();
                });
            }
        },

        redo() {
            if (this.canRedo) {
                this.historyIndex++;
                const state = this.history[this.historyIndex];
                this.content = state.content;
                this.updateWordCount();
                this.$nextTick(() => {
                    this.$refs.editor.setSelectionRange(state.selectionStart, state.selectionEnd);
                    this.updateSelection();
                });
            }
        },

        handleInput() {
            this.updateWordCount();
            // Debounced save to history (save after 1 second of inactivity)
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.saveToHistory();
            }, 1000);
        },

        updateWordCount() {
            const words = this.content.trim().split(/\s+/).filter(word => word.length > 0);
            this.wordCount = this.content.trim() === '' ? 0 : words.length;
        },

   
     

       
        handleKeydown(event) {
            // Handle keyboard shortcuts
            if (event.ctrlKey || event.metaKey) {
                switch(event.key.toLowerCase()) {
                    case 'z':
                        event.preventDefault();
                        if (event.shiftKey) {
                            this.redo();
                        } else {
                            this.undo();
                        }
                        break;
                    case 'y':
                        event.preventDefault();
                        this.redo();
                        break;
                }
            }
        }
    }
}
</script>