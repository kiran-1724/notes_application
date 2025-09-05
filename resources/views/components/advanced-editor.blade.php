@props(['name', 'value' => '', 'placeholder' => 'Start writing...', 'required' => false])

<div x-data="simpleEditor(@js($value))" 
     x-init="init()"
     class="border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-slate-500 focus-within:border-slate-500 transition-colors duration-200">
    
    <!-- Toolbar -->
    <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
        <!-- Undo/Redo -->
        <div class="flex items-center gap-1">
            <button type="button" @click="undo()" :disabled="!canUndo" :class="canUndo ? 'hover:bg-gray-200' : 'opacity-50 cursor-not-allowed'" class="p-2 rounded transition-colors duration-200" title="Undo (Ctrl+Z)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button type="button" @click="redo()" :disabled="!canRedo" :class="canRedo ? 'hover:bg-gray-200' : 'opacity-50 cursor-not-allowed'" class="p-2 rounded transition-colors duration-200" title="Redo (Ctrl+Y)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/></svg>
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
            name="{{ $name }}"
            x-model="content"
            @input="handleInput()"
            @keydown="handleKeydown($event)"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            rows="12"
            class="block w-full px-4 py-3 border-0 rounded-b-lg resize-y focus:outline-none placeholder-gray-500"
            style="font-family: 'Inter', system-ui, -apple-system, sans-serif; line-height: 1.6;"></textarea>
    </div>
</div>

<script>
function simpleEditor(initialValue = '') {
    return {
        content: initialValue,
        wordCount: 0,
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
            this.saveToHistory(this.content);
            
            this.$watch('value', (newValue) => {
                if(newValue !== this.content) {
                    this.content = newValue;
                    this.saveToHistory(newValue);
                }
            });
        },

        saveToHistory(contentToSave) {
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(contentToSave);
            this.historyIndex = this.history.length - 1;
            
            if (this.history.length > 50) {
                this.history.shift();
                this.historyIndex--;
            }
        },

        undo() {
            if (this.canUndo) {
                this.historyIndex--;
                this.content = this.history[this.historyIndex];
                this.updateWordCount();
            }
        },

        redo() {
            if (this.canRedo) {
                this.historyIndex++;
                this.content = this.history[this.historyIndex];
                this.updateWordCount();
            }
        },

        handleInput() {
            this.updateWordCount();
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.saveToHistory(this.content);
            }, 500); // Save after 500ms of inactivity
        },

        updateWordCount() {
            if (!this.content || this.content.trim() === '') {
                this.wordCount = 0;
                return;
            }
            const words = this.content.trim().split(/\s+/);
            this.wordCount = words.length;
        },
       
        handleKeydown(event) {
            if (event.ctrlKey || event.metaKey) {
                switch(event.key.toLowerCase()) {
                    case 'z':
                        event.preventDefault();
                        this.undo();
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