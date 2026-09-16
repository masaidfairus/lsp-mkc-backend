<x-filament-widgets::widget class="mt-6">
    <div class="flex flex-col gap-6">
        
        {{-- 1. Header & Filters Card --}}
        <x-filament::section>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-white/10">
                <div>
                    <h3 class="text-lg font-bold tracking-tight text-gray-950 dark:text-white flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-chart-bar" class="h-6 w-6 text-primary-500" style="color: var(--primary-500);" />
                        Laporan & Analisis Permohonan Sertifikasi (APL.01)
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Analisis real-time berdasarkan tipe pendaftaran asesi, periode waktu pendaftaran, dan status tahapan sertifikasi.
                    </p>
                </div>
                
                <div class="shrink-0">
                    <x-filament::button
                        href="{{ route('admin.reports.asesi-type.pdf', ['asesi_type' => $filterAsesiType, 'period' => $filterPeriod, 'status' => $filterStatus, 'date_from' => $filterDateFrom, 'date_to' => $filterDateTo]) }}"
                        tag="a"
                        target="_blank"
                        icon="heroicon-o-printer"
                        color="success"
                    >
                        Cetak Laporan PDF
                    </x-filament::button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {{-- Filter Tipe Asesi --}}
                <div>
                    <label for="filterAsesiType" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Tipe Asesi</span>
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="filterAsesiType" id="filterAsesiType">
                            <option value="all">Semua Tipe Asesi</option>
                            <option value="pribadi">Pribadi (Mandiri)</option>
                            <option value="institusi">Instansi / Lembaga</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                {{-- Filter Periode --}}
                <div>
                    <label for="filterPeriod" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Periode Waktu</span>
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="filterPeriod" id="filterPeriod">
                            <option value="all">Semua Waktu</option>
                            <option value="today">Hari Ini</option>
                            <option value="month">Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                            <option value="custom">📅 Rentang Tanggal...</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                {{-- Filter Status --}}
                <div>
                    <label for="filterStatus" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Status Sertifikasi</span>
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="filterStatus" id="filterStatus">
                            <option value="all">Semua Status</option>
                            <option value="permohonan">Permohonan (Draft / Terkirim / Bayar)</option>
                            <option value="proses">Proses Sertifikasi (Dalam Tinjauan / Uji)</option>
                            <option value="selesai">Selesai (Kompeten / Lulus APL.02)</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
            </div>

            {{-- Date Range Inputs (conditional) --}}
            @if($filterPeriod === 'custom')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-white/10">
                <div>
                    <label for="filterDateFrom" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">📅 Dari Tanggal</span>
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="date" wire:model.live.debounce.500ms="filterDateFrom" id="filterDateFrom" />
                    </x-filament::input.wrapper>
                </div>
                <div>
                    <label for="filterDateTo" class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">📅 Sampai Tanggal</span>
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="date" wire:model.live.debounce.500ms="filterDateTo" id="filterDateTo" />
                    </x-filament::input.wrapper>
                </div>
            </div>
            @endif
        </x-filament::section>

        {{-- 2. Statistics Cards Grid --}}
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            {{-- Card Total --}}
            <div class="relative flex flex-col justify-between rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-4">
                    <x-filament::icon icon="heroicon-o-document-duplicate" class="h-10 w-10 text-primary-500" style="color: var(--primary-500);" />
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Permohonan</span>
                        <span class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $total }}</span>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium text-primary-600 dark:text-primary-400">
                    Menampilkan seluruh data <br> berdasarkan filter
                </div>
            </div>

            {{-- Card Pribadi --}}
            <div class="relative flex flex-col justify-between rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-4">
                    <x-filament::icon icon="heroicon-o-user" class="h-10 w-10 text-info-500" style="color: var(--info-500, #0ea5e9);" />
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Pribadi</span>
                        <span class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $pribadi }}</span>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium text-info-600 dark:text-info-400">
                    {{ $total > 0 ? round(($pribadi / $total) * 100) : 0 }}% dari total keseluruhan
                </div>
            </div>

            {{-- Card Instansi --}}
            <div class="relative flex flex-col justify-between rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-4">
                    <x-filament::icon icon="heroicon-o-building-office-2" class="h-10 w-10 text-success-500" style="color: var(--success-500, #10b981);" />
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Instansi</span>
                        <span class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $institusi }}</span>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium text-success-600 dark:text-success-400">
                    {{ $total > 0 ? round(($institusi / $total) * 100) : 0 }}% dari total keseluruhan
                </div>
            </div>

            {{-- Card Selesai --}}
            <div class="relative flex flex-col justify-between rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-4">
                    <x-filament::icon icon="heroicon-o-check-badge" class="h-10 w-10 text-warning-500" style="color: var(--warning-500, #f59e0b);" />
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Selesai</span>
                        <span class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $selesai }}</span>
                    </div>
                </div>
                <div class="mt-4 text-xs font-medium text-warning-600 dark:text-warning-400">
                    {{ $total > 0 ? round(($selesai / $total) * 100) : 0 }}% tingkat penyelesaian
                </div>
            </div>
        </div>

        {{-- 3. Table Section --}}
        <div class="fi-ta-ctn flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="fi-ta-header flex flex-col gap-3 p-4 sm:px-6 border-b border-gray-200 dark:border-white/10">
                {{-- Row 1: Title + badge (always shown) --}}
                <div class="flex items-center gap-3">
                    <h4 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Rincian Data Pendaftaran
                    </h4>
                    <x-filament::badge color="gray">
                        Menampilkan {{ $registrations->count() }} dari {{ $registrations->total() }} Data
                    </x-filament::badge>
                </div>

                {{-- Row 2: Export controls (only shown when at least 1 asesi is selected) --}}
                @if(count($selectedAsesi) > 0)
                <div class="flex flex-wrap items-center gap-3 pt-1 border-t border-gray-100 dark:border-white/5">
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 dark:text-primary-400 whitespace-nowrap">
                        <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4" />
                        {{ count($selectedAsesi) }} Dipilih
                    </span>
                    <div class="flex-1 min-w-[180px] max-w-xs">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="exportFilename" placeholder="Nama File (Opsional)" />
                        </x-filament::input.wrapper>
                    </div>
                    <x-filament::button wire:click="exportSelectedBlanko" color="success" icon="heroicon-o-arrow-down-tray" class="whitespace-nowrap shrink-0">
                        Export Blanko (.xlsx)
                    </x-filament::button>
                </div>
                @endif
            </div>
            
            <div class="fi-ta-content divide-y divide-gray-200 overflow-x-auto dark:divide-white/10">
                <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="fi-ta-header-cell px-3 py-3.5 w-12 sm:first-of-type:ps-6 text-center">
                                <input type="checkbox" wire:click="toggleSelectAll" @if($selectAll) checked @endif class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-primary-500" />
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:last-of-type:pe-6">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Asesi & Skema</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Tipe Asesi</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Status</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-right">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-end">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Tanggal Daftar</span>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                        @forelse($registrations as $reg)
                            <tr class="fi-ta-row hover:bg-gray-50 dark:hover:bg-white/5 transition duration-75">
                                <td class="fi-ta-cell p-0 px-3 w-12 sm:first-of-type:ps-6 text-center">
                                    <input type="checkbox" wire:model.live="selectedAsesi" value="{{ $reg->id }}" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-primary-500" />
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex flex-col justify-center gap-y-1 px-3 py-4">
                                        <div class="text-sm text-gray-950 dark:text-white font-medium">
                                            {{ $reg->candidate->name ?? 'User Tidak Ditemukan' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                            <x-filament::icon icon="heroicon-m-academic-cap" class="w-4 h-4 text-gray-400" />
                                            {{ $reg->scheme->name ?? 'Skema Tidak Dipilih' }}
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex flex-col justify-center gap-y-1 px-3 py-4">
                                        <div class="flex flex-col gap-1 items-start">
                                            @if($reg->asesi_type === 'institusi')
                                                <x-filament::badge color="success" icon="heroicon-m-building-office">
                                                    Instansi
                                                </x-filament::badge>
                                                @if($reg->institution_name)
                                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-white/5 px-2 py-0.5 rounded border border-gray-200 dark:border-white/10 max-w-[150px] truncate" title="{{ $reg->institution_name }}">
                                                        {{ $reg->institution_name }}
                                                    </span>
                                                @endif
                                            @else
                                                <x-filament::badge color="info" icon="heroicon-m-user">
                                                    Pribadi
                                                </x-filament::badge>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex flex-col justify-center gap-y-1 px-3 py-4">
                                        @php
                                            $statusText = $reg->status ? $reg->status->getLabel() : '-';
                                            $statusColor = $reg->status ? $reg->status->getColor() : 'gray';
                                            $statusIcon = $reg->status ? $reg->status->getIcon() : null;
                                        @endphp

                                        <div>
                                            <x-filament::badge :color="$statusColor" :icon="$statusIcon">
                                                {{ $statusText }}
                                            </x-filament::badge>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 text-right">
                                    <div class="fi-ta-col-wrp flex flex-col justify-center gap-y-1 px-3 py-4 text-right items-end">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $reg->created_at ? $reg->created_at->translatedFormat('d M Y H:i') : '-' }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <x-filament::icon icon="heroicon-o-inbox" class="h-10 w-10 text-gray-400 dark:text-gray-500" />
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tidak ada permohonan sertifikasi yang cocok dengan filter aktif.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($registrations->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-white/10">
                    {{ $registrations->links('filament::components.pagination') }}
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
