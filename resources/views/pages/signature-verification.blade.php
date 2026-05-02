<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tanda Tangan Digital - LSP MKC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-4">
    <!-- Background Decor -->
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-500/5 blur-[120px]"></div>
    </div>

    <div class="max-w-md w-full animate-in fade-in zoom-in duration-500">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex p-3 rounded-2xl bg-white shadow-sm mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">VERIFIKASI DIGITAL</h1>
            <p class="text-slate-500 text-sm font-medium mt-1 uppercase tracking-widest">LSP MITRA KARYA CERDAS</p>
        </div>

        <!-- Main Card -->
        <div class="glass-card rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
            <!-- Status Header -->
            <div class="bg-emerald-500/10 px-6 py-4 flex items-center justify-center gap-2 border-b border-emerald-500/10">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-emerald-700 text-xs font-bold uppercase tracking-wider">Tanda Tangan Valid & Terverifikasi</span>
            </div>

            <div class="p-8">
                <!-- Signature Preview -->
                <div class="relative group mb-8">
                    <div class="absolute inset-0 bg-slate-100 rounded-3xl -rotate-2 group-hover:rotate-0 transition-transform duration-300"></div>
                    <div class="relative bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-center min-h-[160px]">
                        <img src="{{ $signature->raw_signature }}" class="max-h-[120px] object-contain grayscale hover:grayscale-0 transition-all duration-500" alt="Digital Signature">
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Penanda Tangan</label>
                        <p class="text-slate-900 font-bold text-lg leading-tight">{{ $user->name }}</p>
                        <p class="text-slate-500 text-xs font-medium mt-0.5">Role: {{ $signature->role }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Label Dokumen</label>
                            <p class="text-slate-900 font-bold text-sm">{{ $signature->document_label }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">ID Registrasi</label>
                            <p class="text-slate-900 font-bold text-sm">#{{ $registration->id }}</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Waktu Tanda Tangan</label>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-slate-600 font-bold text-sm">{{ $signature->signed_at->format('d F Y') }} <span class="text-slate-400 font-medium ml-1">{{ $signature->signed_at->format('H:i:s') }} WIB</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                <a href="https://lsp-mkc.id" class="flex items-center justify-center gap-2 w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-900/10">
                    Selesai
                </a>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[10px] font-medium mt-8 leading-relaxed px-8">
            Dokumen ini merupakan arsip digital LSP Mitra Karya Cerdas. Keaslian tanda tangan dijamin melalui sistem kriptografi internal.
        </p>
    </div>
</body>
</html>
