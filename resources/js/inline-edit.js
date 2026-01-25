// Inline Editing Component
// Add this to app.js or create separate inline-edit.js

document.addEventListener('alpine:init', () => {
    Alpine.data('inlineEdit', (initialValue, saveRoute, fieldName) => ({
        editing: false,
        value: initialValue,
        originalValue: initialValue,
        saving: false,
        error: null,
        
        startEdit() {
            this.editing = true;
            this.$nextTick(() => {
                const input = this.$refs.input;
                if (input) {
                    input.focus();
                    input.select();
                }
            });
        },
        
        cancel() {
            this.value = this.originalValue;
            this.editing = false;
            this.error = null;
        },
        
        async save() {
            if (this.value === this.originalValue) {
                this.editing = false;
                return;
            }
            
            this.saving = true;
            this.error = null;
            
            try {
                const response = await fetch(saveRoute, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        [fieldName]: this.value
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to save');
                }
                
                const data = await response.json();
                
                this.originalValue = this.value;
                this.editing = false;
                
                // Show success toast
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: 'Perubahan berhasil disimpan', type: 'success' }
                }));
                
            } catch (error) {
                this.error = 'Gagal menyimpan perubahan';
                console.error('Save error:', error);
            } finally {
                this.saving = false;
            }
        },
        
        handleKeydown(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.save();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                this.cancel();
            }
        }
    }));
});
