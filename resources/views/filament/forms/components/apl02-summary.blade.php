<div class="space-y-6">
    @php
        $record = $getRecord();
        $selfAssessments = $record ? $record->selfAssessments()->with(['element.competencyUnit', 'element.performanceCriteria'])->get() : collect();
        
        $grouped = $selfAssessments->groupBy(function($item) {
            if (!$item->element || !$item->element->competencyUnit) return 'Unit Tidak Diketahui';
            return $item->element->competencyUnit->unit_code . ' - ' . $item->element->competencyUnit->unit_title;
        });
    @endphp

    @if($grouped->isEmpty())
        <div class="text-center p-6 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            Asesi belum mengisi Asesmen Mandiri (APL.02).
        </div>
    @else
        @foreach($grouped as $unitTitle => $assessments)
            <div class="border rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-sm dark:border-gray-800">
                <div class="bg-primary-50 dark:bg-primary-900/20 px-4 py-3 border-b border-primary-100 dark:border-primary-900/30 flex items-center gap-2">
                    <x-heroicon-o-briefcase class="w-5 h-5 text-primary-600 dark:text-primary-400"/>
                    <h3 class="font-bold text-sm text-primary-800 dark:text-primary-300">{{ $unitTitle }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-b dark:border-gray-800 w-1/3">Elemen Kompetensi</th>
                                <th class="px-4 py-3 border-b dark:border-gray-800">Kriteria Unjuk Kerja</th>
                                <th class="px-4 py-3 border-b dark:border-gray-800 text-center w-24">Kompeten</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($assessments as $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                    <td class="px-4 py-4 align-top font-medium text-gray-900 dark:text-gray-200">
                                        {{ $item->element->title ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 align-top">
                                        <ul class="list-none space-y-2 text-gray-600 dark:text-gray-400">
                                            @if($item->element && $item->element->performanceCriteria)
                                                @foreach($item->element->performanceCriteria as $kuk)
                                                    <li class="flex gap-2">
                                                        <span class="text-primary-500 mt-0.5">•</span>
                                                        <span class="leading-relaxed">{{ $kuk->name }}</span>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li>-</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="px-4 py-4 align-top text-center">
                                        @if($item->is_competent)
                                            <div class="inline-flex items-center justify-center p-1.5 rounded-full bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400 ring-1 ring-inset ring-success-500/20">
                                                <x-heroicon-o-check class="w-5 h-5"/>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center justify-center p-1.5 rounded-full bg-danger-50 text-danger-600 dark:bg-danger-500/10 dark:text-danger-400 ring-1 ring-inset ring-danger-500/20">
                                                <x-heroicon-o-x-mark class="w-5 h-5"/>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
