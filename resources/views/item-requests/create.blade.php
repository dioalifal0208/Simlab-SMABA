<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">{{ __('items.requests.title') }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ __('items.requests.subtitle') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 space-y-6">
                    <div class="bg-indigo-600/5 border border-indigo-600/10 text-sm text-gray-700 p-4 rounded-lg">
                        <p class="font-semibold text-indigo-600">{{ __('items.requests.note_title') }}</p>
                        <p class="mt-1">{{ __('items.requests.note_text') }}</p>
                    </div>

                    <form method="POST" action="{{ route('item-requests.store') }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.name') }}</label>
                                <input name="nama_alat" value="{{ old('nama_alat') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" />
                                <x-input-error :messages="$errors->get('nama_alat')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.type') }}</label>
                                <input name="tipe" value="{{ old('tipe') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" />
                                <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                            </div>
                        </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.quantity') }}</label>
                                    <input type="number" min="1" name="jumlah" value="{{ old('jumlah', 1) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" />
                                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.unit') }}</label>
                                <input name="satuan" value="{{ old('satuan', 'unit') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" />
                                <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.lab') }}</label>
                                <select name="laboratorium" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required {{ auth()->user()->role === 'admin' ? '' : 'disabled' }}>
                                    <option value="Biologi" @selected(old('laboratorium', auth()->user()->laboratorium ?? 'Biologi') === 'Biologi')>Biologi</option>
                                    <option value="Fisika" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Fisika')>Fisika</option>
                                    <option value="Bahasa" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Bahasa')>Bahasa</option>
                                    <option value="Komputer 1" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Komputer 1')>Komputer 1</option>
                                    <option value="Komputer 2" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Komputer 2')>Komputer 2</option>
                                    <option value="Komputer 3" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Komputer 3')>Komputer 3</option>
                                    <option value="Komputer 4" @selected(old('laboratorium', auth()->user()->laboratorium ?? '') === 'Komputer 4')>Komputer 4</option>
                                </select>
                                @if(auth()->user()->role !== 'admin')
                                    <input type="hidden" name="laboratorium" value="{{ old('laboratorium', auth()->user()->laboratorium) }}">
                                    <p class="text-xs text-gray-500 mt-1">{{ __('items.filters.locked_lab') }}</p>
                                @endif
                                <x-input-error :messages="$errors->get('laboratorium')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.urgency') }}</label>
                                <select name="urgensi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                                    <option value="normal" {{ old('urgensi') === 'normal' ? 'selected' : '' }}>{{ __('items.status.normal') }}</option>
                                    <option value="mendesak" {{ old('urgensi') === 'mendesak' ? 'selected' : '' }}>{{ __('items.status.urgent') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('urgensi')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.description') }}</label>
                            <textarea name="deskripsi" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('items.requests.labels.urgency_reason') }}</label>
                            <textarea name="alasan_urgent" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('alasan_urgent') }}</textarea>
                            <x-input-error :messages="$errors->get('alasan_urgent')" class="mt-2" />
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                                {{ __('items.actions.send_request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

