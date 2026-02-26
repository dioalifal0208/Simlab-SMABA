{{-- Bulk Actions Component --}}
{{-- Usage: @include('components.bulk-actions', ['items' => $items, 'actions' => [...], 'modelName' => 'Item']) --}}

@php
    $actions = $actions ?? [];
    $modelName = $modelName ?? 'Item';
@endphp

<div x-data="{
    selectedIds: [],
    selectAll: false,
    
    toggleAll() {
        if (this.selectAll) {
            this.selectedIds = {{ json_encode($items->pluck('id')->toArray()) }};
        } else {
            this.selectedIds = [];
        }
    },
    
    toggleItem(id) {
        const index = this.selectedIds.indexOf(id);
        if (index > -1) {
            this.selectedIds.splice(index, 1);
        } else {
            this.selectedIds.push(id);
        }
        this.selectAll = this.selectedIds.length === {{ $items->count() }};
    },
    
    isSelected(id) {
        return this.selectedIds.includes(id);
    },
    
    performBulkAction(action) {
        if (this.selectedIds.length === 0) {
            alert('Pilih minimal satu item terlebih dahulu');
            return;
        }
        
        if (action.confirm) {
            if (!confirm(action.confirm.replace(':count', this.selectedIds.length))) {
                return;
            }
        }
        
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action.route;
        
        // CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Method spoofing if needed
        if (action.method && action.method !== 'POST') {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = action.method;
            form.appendChild(methodInput);
        }
        
        // Selected IDs
        this.selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}" class="space-y-3">

    {{-- Bulk Actions Toolbar --}}
    <div 
        x-show="selectedIds.length > 0" 
        x-transition
        class="bg-indigo-600 text-white rounded-lg p-4 flex items-center justify-between"
    >
        <div class="flex items-center gap-3">
            <span class="font-semibold" x-text="selectedIds.length + ' item dipilih'"></span>
            <button @click="selectedIds = []; selectAll = false" class="text-sm underline hover:no-underline">
                Batalkan Pilihan
            </button>
        </div>
        
        <div class="flex items-center gap-2">
            @foreach($actions as $action)
                <button 
                    @click="performBulkAction({{ json_encode($action) }})"
                    class="px-4 py-2 {{ $action['class'] ?? 'bg-white text-indigo-600' }} rounded-lg hover:opacity-90 transition-opacity font-semibold text-sm"
                >
                    @if(isset($action['icon']))
                        <i class="fas {{ $action['icon'] }} mr-2"></i>
                    @endif
                    {{ $action['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Table with Checkboxes --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full table-striped table-hover">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 w-12">
                        <input 
                            type="checkbox" 
                            x-model="selectAll"
                            @change="toggleAll()"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        >
                    </th>
                    {{ $tableHeaders ?? '' }}
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="py-3 px-4">
                            <input 
                                type="checkbox" 
                                :checked="isSelected({{ $item->id }})"
                                @change="toggleItem({{ $item->id }})"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                            >
                        </td>
                        {{ $tableRow ?? '' }}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

