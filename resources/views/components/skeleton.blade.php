{{-- Loading Skeleton Component --}}
{{-- Usage: @include('components.skeleton', ['type' => 'card', 'count' => 3]) --}}

@php
    $type = $type ?? 'card';
    $count = $count ?? 1;
@endphp

@if($type === 'card')
    @for($i = 0; $i < $count; $i++)
        <div class="bg-white rounded-lg border border-gray-200 p-6 animate-pulse">
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
            <div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div>
            <div class="h-3 bg-gray-200 rounded w-2/3"></div>
        </div>
    @endfor
@elseif($type === 'table-row')
    @for($i = 0; $i < $count; $i++)
        <tr class="animate-pulse">
            <td class="py-4 px-6"><div class="h-4 bg-gray-200 rounded w-3/4"></div></td>
            <td class="py-4 px-6"><div class="h-4 bg-gray-200 rounded w-1/2"></div></td>
            <td class="py-4 px-6"><div class="h-4 bg-gray-200 rounded w-2/3"></div></td>
            <td class="py-4 px-6"><div class="h-4 bg-gray-200 rounded w-1/4"></div></td>
        </tr>
    @endfor
@elseif($type === 'stat-card')
    @for($i = 0; $i < $count; $i++)
        <div class="bg-white rounded-xl border border-gray-100 p-6 animate-pulse">
            <div class="h-3 bg-gray-200 rounded w-1/3 mb-3"></div>
            <div class="h-8 bg-gray-200 rounded w-1/4 mb-2"></div>
            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
        </div>
    @endfor
@elseif($type === 'list-item')
    @for($i = 0; $i < $count; $i++)
        <div class="flex items-center gap-3 p-3 animate-pulse">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0"></div>
            <div class="flex-1">
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
            </div>
        </div>
    @endfor
@elseif($type === 'text')
    @for($i = 0; $i < $count; $i++)
        <div class="animate-pulse mb-3">
            <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-5/6 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-4/5"></div>
        </div>
    @endfor
@endif
