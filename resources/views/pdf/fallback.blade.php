<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | LSP MKC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200 overflow-hidden border border-slate-100">

        <!-- Header Progress -->
        <div class="bg-white px-8 pt-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 {{ $isSignatureError ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600' }} rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        📄
                    </div>
                    <div>
                        <p class="font-extrabold text-xl text-slate-900 leading-tight">
                            {{ str_replace([' Belum Siap', ' Belum Ditandatangani'], '', $title) }}
                        </p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status Dokumen</p>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="flex items-center gap-1.5 mb-10">
                <div class="h-2.5 flex-1 bg-emerald-500 rounded-full shadow-sm shadow-emerald-100"></div>
                <div class="h-2.5 flex-1 {{ $isSignatureError ? 'bg-amber-400' : 'bg-emerald-500' }} rounded-full"></div>
                <div class="h-2.5 flex-1 bg-slate-100 rounded-full"></div>
                <div class="h-2.5 flex-1 bg-slate-100 rounded-full"></div>
            </div>
        </div>

        <div class="px-8 pb-10 text-center">
            <div class="mx-auto w-24 h-24 {{ $isSignatureError ? 'bg-amber-50 text-amber-500 border-amber-100' : 'bg-indigo-50 text-indigo-500 border-indigo-100' }} rounded-[2rem] border-2 flex items-center justify-center text-6xl mb-8 shadow-xl shadow-slate-50">
                {{ $icon }}
            </div>
            
            <h1 class="text-2xl font-extrabold text-slate-900 mb-3 tracking-tight">{{ $title }}</h1>
            <p class="text-slate-500 font-medium leading-relaxed px-2">{{ $message }}</p>

            <div class="mt-10 bg-slate-50 rounded-[2rem] p-6 text-left border border-slate-100">
                <p class="font-extrabold text-slate-900 text-xs uppercase tracking-widest mb-4">Informasi Penting:</p>
                <ul class="space-y-3">
                    <li class="flex gap-3 items-start">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-xs font-bold">✓</div>
                        <p class="text-xs font-bold text-slate-500 leading-normal">
                            {{ $isSignatureError ? 'Asesor wajib menandatangani dokumen ini di menu Inbox.' : 'Admin harus melakukan verifikasi dokumen asesi.' }}
                        </p>
                    </li>
                    <li class="flex gap-3 items-start">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-xs font-bold">✓</div>
                        <p class="text-xs font-bold text-slate-500 leading-normal">
                            Notifikasi akan terkirim secara otomatis saat dokumen siap dicetak.
                        </p>
                    </li>
                </ul>
            </div>

            <a href="{{ route('filament.admin.resources.assessment-registrations.edit', $registration) }}" 
               class="block w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-extrabold py-5 rounded-3xl mt-10 transition-all shadow-xl shadow-slate-200 active:scale-95">
                KEMBALI KE PANEL ADMIN
            </a>
        </div>
    </div>

</body>
</html>
