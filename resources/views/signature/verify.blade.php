<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>E-Signature Verification | LSP Mitra Karya Cemerlang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .verified-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4 selection:bg-indigo-100">

    <div class="w-full max-w-lg">
        <!-- Logo / Brand Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 bg-white px-5 py-2.5 rounded-2xl shadow-sm border border-gray-100 mb-6 group hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-sm font-extrabold text-gray-900 tracking-tight leading-none uppercase">LSP MKC</div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Authentic Signature</div>
                </div>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Verifikasi Digital</h1>
            <p class="text-gray-500 mt-2 text-sm font-medium">Dokumen ini telah divalidasi oleh sistem e-signature LSP MKC</p>
        </div>

        @if($sigData)
        <div class="glass border border-white shadow-2xl shadow-indigo-100 rounded-[2.5rem] overflow-hidden transition-all hover:shadow-indigo-200">
            <!-- Header Status -->
            <div class="bg-gradient-to-br from-emerald-500 via-green-600 to-teal-600 px-8 py-7 flex items-center gap-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg width="120" height="120" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="bg-white/20 p-3.5 rounded-2xl backdrop-blur-md border border-white/30 shadow-xl verified-pulse">
                    <svg width="32" height="32" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.333 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="text-white text-xl font-extrabold tracking-tight">Data Terverifikasi</div>
                    <div class="text-white/80 text-xs font-semibold uppercase tracking-widest mt-1">E-Signature Valid & Aktif</div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Penandatangan -->
                    <div class="flex items-center gap-5 p-5 bg-slate-50 rounded-3xl border border-slate-100 group transition-all hover:bg-indigo-50/50 hover:border-indigo-100">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-white shadow-sm text-indigo-600 group-hover:scale-110 transition-transform">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em] mb-1">Penandatangan</div>
                            <div class="text-[17px] font-extrabold text-gray-900 leading-tight">{{ strtoupper($sigData['n'] ?? '-') }}</div>
                            <div class="text-xs text-indigo-500 font-bold mt-1 uppercase tracking-wider">{{ $sigData['r'] ?? '—' }}</div>
                        </div>
                    </div>

                    <!-- Dokumen -->
                    <div class="flex items-center gap-5 p-5 bg-slate-50 rounded-3xl border border-slate-100 group transition-all hover:bg-sky-50/50 hover:border-sky-100">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-white shadow-sm text-sky-600 group-hover:scale-110 transition-transform">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em] mb-1">Nama Dokumen</div>
                            <div class="text-[17px] font-extrabold text-gray-900 leading-tight">{{ $sigData['d'] ?? '—' }}</div>
                            <div class="text-xs text-sky-500 font-bold mt-1">ID VERIFIKASI: #{{ strtoupper(substr(md5($sigData['n'].$sigData['t']), 0, 8)) }}</div>
                        </div>
                    </div>

                    <!-- Waktu -->
                    <div class="flex items-center gap-5 p-5 bg-slate-50 rounded-3xl border border-slate-100 group transition-all hover:bg-amber-50/50 hover:border-amber-100">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-white shadow-sm text-amber-600 group-hover:scale-110 transition-transform">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em] mb-1">Waktu Penandatanganan</div>
                            <div class="text-[17px] font-extrabold text-gray-900 leading-tight">
                                @php
                                    $dt = \Carbon\Carbon::parse($sigData['t']);
                                @endphp
                                {{ $dt->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-xs text-amber-500 font-bold mt-1 uppercase tracking-widest">{{ $dt->format('H:i:s') }} WIB</div>
                        </div>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="bg-indigo-600/5 rounded-2xl p-5 flex items-center gap-4 border border-indigo-600/10">
                    <div class="w-1.5 h-10 bg-indigo-600 rounded-full"></div>
                    <div class="text-xs text-indigo-900 leading-relaxed font-medium">
                        Tanda tangan ini dibuat secara elektronik menggunakan sistem keamanan enkripsi LSP MKC dan memiliki kekuatan hukum yang setara dengan tanda tangan basah.
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 border-t border-slate-100 px-8 py-5 flex items-center justify-between">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Verification Server <span class="text-indigo-600">v2.0</span>
                </div>
                <div class="flex items-center gap-2 text-[11px] font-extrabold text-emerald-600 bg-emerald-50 px-4 py-1.5 rounded-full border border-emerald-100 shadow-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full verified-pulse"></span>
                    SECURITY GUARANTEED
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-[2.5rem] shadow-2xl p-12 text-center border border-gray-100">
            <div class="mx-auto w-24 h-24 bg-red-50 rounded-3xl flex items-center justify-center text-red-500 mb-8 border border-red-100 shadow-lg shadow-red-50 animate-bounce">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-3">Tanda Tangan Tidak Valid</h2>
            <p class="text-gray-500 font-medium px-4">QR Code tidak terdaftar dalam database resmi atau data telah dihapus oleh administrator.</p>
            <div class="mt-8">
                <a href="/" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors uppercase tracking-widest underline decoration-2 underline-offset-8">Kembali ke Beranda</a>
            </div>
        </div>
        @endif
        
        <p class="text-center mt-10 text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} LSP MITRA KARYA CEMERLANG
        </p>
    </div>

</body>
</html>