<section class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-red-100" data-aos="fade-up" data-aos-delay="250" data-aos-once="true">
    <div class="p-6 md:p-8 space-y-4">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold">
                !
            </div>
            <div>
                <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">{{ __('profile.sections.delete.badge') }}</p>
                <h3 class="text-xl font-bold text-smaba-text">{{ __('profile.sections.delete.title') }}</h3>
                <p class="text-sm text-gray-600">{{ __('profile.sections.delete.subtitle') }}</p>
            </div>
        </div>

        <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                <li>{{ __('profile.sections.delete.warning_list.item1') }}</li>
                <li>{{ __('profile.sections.delete.warning_list.item2') }}</li>
                <li>{{ __('profile.sections.delete.warning_list.item3') }}</li>
            </ul>
        </div>

        <x-danger-button
            class="mt-1"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('profile.buttons.delete_account') }}</x-danger-button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
                @csrf
                @method('delete')

                <div class="space-y-2">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('profile.sections.delete.confirmation_title') }}</h2>
                    <p class="text-sm text-gray-600">{{ __('profile.sections.delete.confirmation_subtitle') }}</p>
                </div>

                <div>
                    <x-input-label for="password" :value="__('profile.labels.password')" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('profile.placeholders.enter_password') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('profile.buttons.cancel') }}
                    </x-secondary-button>

                    <x-danger-button>
                        {{ __('profile.buttons.delete_my_account') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</section>
