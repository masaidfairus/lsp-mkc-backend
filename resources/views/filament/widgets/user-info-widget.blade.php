<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-6 p-2">

            {{-- Avatar Inisial --}}
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-primary-500 text-white text-2xl font-bold shrink-0 select-none">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>

            {{-- Info User --}}
            <div class="flex flex-col gap-2 flex-1">

                {{-- Nama --}}
                <div class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->name }}
                </div>

                {{-- Email --}}
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-envelope class="w-4 h-4 shrink-0" />
                    <span>{{ auth()->user()->email }}</span>
                </div>

                {{-- Role Badges --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <x-heroicon-o-shield-check class="w-4 h-4 text-gray-400 shrink-0" />
                    @forelse (auth()->user()->getRoleNames() as $role)
                        <span class="inline-flex items-center py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200 capitalize">
                            {{ $role }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400">Tidak ada role</span>
                    @endforelse
                </div>

            </div>

            {{-- Tombol Sign Out --}}
            <div class="ml-auto shrink-0">
                <form method="POST" action="{{ filament()->getLogoutUrl() }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                        Sign out
                    </button>
                </form>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>