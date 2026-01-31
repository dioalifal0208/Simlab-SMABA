// Drag and Drop Utility
// Reusable drag & drop functionality

class DragDropManager {
    constructor(options = {}) {
        this.container = options.container;
        this.itemSelector = options.itemSelector || '.draggable';
        this.handleSelector = options.handleSelector || null;
        this.onDrop = options.onDrop || (() => {});
        this.onReorder = options.onReorder || (() => {});
        
        this.draggedElement = null;
        this.placeholder = null;
        
        this.init();
    }
    
    init() {
        if (!this.container) return;
        
        // Make items draggable
        this.container.querySelectorAll(this.itemSelector).forEach(item => {
            this.makeDraggable(item);
        });
    }
    
    makeDraggable(element) {
        element.setAttribute('draggable', 'true');
        
        const dragHandle = this.handleSelector 
            ? element.querySelector(this.handleSelector)
            : element;
        
        if (!dragHandle) return;
        
        dragHandle.addEventListener('dragstart', (e) => this.handleDragStart(e, element));
        element.addEventListener('dragend', (e) => this.handleDragEnd(e));
        element.addEventListener('dragover', (e) => this.handleDragOver(e));
        element.addEventListener('drop', (e) => this.handleDrop(e, element));
    }
    
    handleDragStart(e, element) {
        this.draggedElement = element;
        element.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', element.innerHTML);
    }
    
    handleDragEnd(e) {
        if (this.draggedElement) {
            this.draggedElement.classList.remove('dragging');
        }
        
        // Remove all drag-over classes
        this.container.querySelectorAll('.drag-over').forEach(el => {
            el.classList.remove('drag-over');
        });
        
        this.draggedElement = null;
    }
    
    handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        
        e.dataTransfer.dropEffect = 'move';
        
        const afterElement = this.getDragAfterElement(e.clientY);
        const currentElement = e.currentTarget;
        
        if (currentElement && currentElement !== this.draggedElement) {
            currentElement.classList.add('drag-over');
        }
        
        return false;
    }
    
    handleDrop(e, element) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        element.classList.remove('drag-over');
        
        if (this.draggedElement && this.draggedElement !== element) {
            // Reorder elements
            const allItems = Array.from(this.container.querySelectorAll(this.itemSelector));
            const draggedIndex = allItems.indexOf(this.draggedElement);
            const targetIndex = allItems.indexOf(element);
            
            if (draggedIndex < targetIndex) {
                element.parentNode.insertBefore(this.draggedElement, element.nextSibling);
            } else {
                element.parentNode.insertBefore(this.draggedElement, element);
            }
            
            // Callback with new order
            const newOrder = Array.from(this.container.querySelectorAll(this.itemSelector))
                .map(item => item.dataset.id || item.id);
            
            this.onReorder(newOrder);
        }
        
        return false;
    }
    
    getDragAfterElement(y) {
        const draggableElements = [
            ...this.container.querySelectorAll(`${this.itemSelector}:not(.dragging)`)
        ];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
    
    // Destroy and cleanup
    destroy() {
        this.container.querySelectorAll(this.itemSelector).forEach(item => {
            item.removeAttribute('draggable');
            item.classList.remove('dragging', 'drag-over');
        });
    }
}

// File Upload Drag & Drop
class FileDropZone {
    constructor(element, options = {}) {
        this.element = element;
        this.onFilesAdded = options.onFilesAdded || (() => {});
        this.acceptedTypes = options.acceptedTypes || [];
        this.maxSize = options.maxSize || 10 * 1024 * 1024; // 10MB default
        
        this.init();
    }
    
    init() {
        if (!this.element) return;
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.element.addEventListener(eventName, this.preventDefaults, false);
        });
        
        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            this.element.addEventListener(eventName, () => this.highlight(), false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            this.element.addEventListener(eventName, () => this.unhighlight(), false);
        });
        
        // Handle dropped files
        this.element.addEventListener('drop', (e) => this.handleDrop(e), false);
        
        // Click to browse
        this.element.addEventListener('click', () => this.browse());
    }
    
    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    highlight() {
        this.element.classList.add('drag-over');
    }
    
    unhighlight() {
        this.element.classList.remove('drag-over');
    }
    
    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        this.handleFiles(files);
    }
    
    handleFiles(files) {
        const validFiles = Array.from(files).filter(file => {
            // Check file type
            if (this.acceptedTypes.length > 0) {
                const fileType = file.type || '';
                const fileExt = file.name.split('.').pop();
                
                const isValid = this.acceptedTypes.some(type => {
                    if (type.startsWith('.')) {
                        return fileExt === type.substring(1);
                    }
                    return fileType.match(type);
                });
                
                if (!isValid) {
                    alert(`File type not accepted: ${file.name}`);
                    return false;
                }
            }
            
            // Check file size
            if (file.size > this.maxSize) {
                alert(`File too large: ${file.name} (max ${this.maxSize / 1024 / 1024}MB)`);
                return false;
            }
            
            return true;
        });
        
        if (validFiles.length > 0) {
            this.onFilesAdded(validFiles);
        }
    }
    
    browse() {
        const input = document.createElement('input');
        input.type = 'file';
        input.multiple = true;
        
        if (this.acceptedTypes.length > 0) {
            input.accept = this.acceptedTypes.join(',');
        }
        
        input.onchange = (e) => {
            this.handleFiles(e.target.files);
        };
        
        input.click();
    }
}

// Export for use
window.DragDropManager = DragDropManager;
window.FileDropZone = FileDropZone;
