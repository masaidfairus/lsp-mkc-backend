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
<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-4 selection:bg-blue-100">
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
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight uppercase italic">VERIFIKASI DIGITAL</h1>
            <p class="text-slate-500 text-[10px] font-bold mt-1 uppercase tracking-[0.3em]">LSP MITRA KARYA CERDAS</p>
        </div>

        @if($sigData && ($sigData['v'] ?? false))
        <!-- Main Card -->
        <div class="glass-card rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
            <!-- Status Header -->
            <div class="bg-emerald-500/10 px-6 py-4 flex items-center justify-center gap-2 border-b border-emerald-500/10">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-emerald-700 text-[10px] font-black uppercase tracking-wider">Tanda Tangan Valid & Terverifikasi</span>
            </div>

            <div class="p-8">
                <!-- Signature Preview -->
                @if($sigData['s'])
                <div class="relative group mb-8">
                    <div class="absolute inset-0 bg-slate-100 rounded-3xl -rotate-2 group-hover:rotate-0 transition-transform duration-300"></div>
                    <div class="relative bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-center min-h-[160px]">
                        <img src="{{ $sigData['s'] }}" class="max-h-[120px] object-contain transition-all duration-500" alt="Digital Signature">
                    </div>
                    <p class="absolute -bottom-4 left-0 right-0 text-center text-[8px] font-black text-slate-300 uppercase tracking-[0.3em]">Spesimen Tanda Tangan</p>
                </div>
                @endif

                <!-- Info Grid -->
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Penanda Tangan</label>
                        <p class="text-slate-900 font-black text-lg leading-tight uppercase italic">{{ $sigData['n'] }}</p>
                        <p class="text-blue-600 text-xs font-bold mt-0.5 uppercase tracking-widest">{{ $sigData['r'] }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nama Dokumen</label>
                            <p class="text-slate-900 font-bold text-sm leading-snug">{{ $sigData['d'] }}</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Waktu Tanda Tangan</label>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-slate-600 font-black text-sm">
                                @php
                                    $dt = \Carbon\Carbon::parse($sigData['t']);
                                @endphp
                                {{ $dt->translatedFormat('d F Y') }} 
                                <span class="text-slate-400 font-bold ml-1">{{ $dt->format('H:i:s') }} WIB</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                <a href="https://lsp-mkc.id" class="flex items-center justify-center gap-2 w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-slate-900/10">
                    Selesai
                </a>
            </div>
        </div>
        @else
        <div class="glass-card rounded-[2.5rem] p-12 text-center">
            <div class="mx-auto w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 17c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase mb-2">Data Tidak Ditemukan</h2>
            <p class="text-slate-500 text-sm font-medium leading-relaxed">QR Code tidak valid atau data tanda tangan digital belum terdaftar di database kami.</p>
            <div class="mt-8">
                <a href="https://lsp-mkc.id" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline decoration-2 underline-offset-4">Kembali ke Beranda</a>
            </div>
        </div>
        @endif

        <p class="text-center text-slate-400 text-[10px] font-bold mt-8 leading-relaxed px-8 uppercase tracking-[0.2em]">
            &copy; {{ date('Y') }} LSP Mitra Karya Cerdas. Seluruh data dijamin keasliannya melalui sistem enkripsi internal.
        </p>
    </div>
</body>
</html>