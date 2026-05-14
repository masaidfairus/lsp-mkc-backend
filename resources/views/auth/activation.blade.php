<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Terverifikasi | LSP MKC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #1e8a3c;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
        }

        /* Animated success icon ring */
        .success-ring {
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Content fade up */
        .fade-up {
            animation: fadeUp 0.5s ease-out both;
        }

        .delay-1 {
            animation-delay: 0.15s;
        }

        .delay-2 {
            animation-delay: 0.3s;
        }

        .delay-3 {
            animation-delay: 0.45s;
        }

        .delay-4 {
            animation-delay: 0.6s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pulse dot */
        .pulse-dot {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }
    </style>
</head>

<body>

    <div class="w-full max-w-sm flex flex-col items-center gap-8">

        {{-- Success Icon --}}
        <div class="success-ring flex flex-col items-center gap-6 mb-6">
            {{-- Outer glow ring --}}
            <div class="relative flex items-center justify-center">
                <div class="absolute w-44 h-44 rounded-full bg-white opacity-10 pulse-dot"></div>
                <div class="absolute w-36 h-36 rounded-full bg-white opacity-15"></div>
                {{-- Icon circle --}}
                <div class="w-28 h-28 rounded-full bg-white flex items-center justify-center shadow-2xl relative z-10">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12.5L11.5 15L15 9.5" stroke="#1e8a3c" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M12 2C6.477 2 2 6.477 2 12C2 17.523 6.477 22 12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2Z"
                            stroke="#1e8a3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Text Block --}}
        <div class="text-center fade-up delay-1">
            <h1 class="text-3xl font-extrabold text-white leading-tight tracking-tight">
                Email Berhasil<br>Diverifikasi!
            </h1>
            <p class="text-white/75 mt-3 text-sm font-medium leading-relaxed px-2">
                Selamat! Akun LSP MKC kamu telah aktif.<br>
                Mulailah perjalanan sertifikasi kompetensimu.
            </p>
        </div>

        {{-- Info Card --}}
        <div class="w-full fade-up delay-2">
            <div class="bg-white/15 backdrop-blur-sm rounded-3xl p-5 border border-white/20 space-y-3">

                {{-- Step 1 --}}
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Lengkapi Profil Asesi</p>
                        <p class="text-white/65 text-xs mt-0.5 leading-relaxed">Masuk dan isi data diri, pendidikan,
                            serta dokumen yang diperlukan.</p>
                    </div>
                </div>

                <div class="border-t border-white/15"></div>

                {{-- Step 2 --}}
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Daftar Skema Kompetensi</p>
                        <p class="text-white/65 text-xs mt-0.5 leading-relaxed">Pilih skema yang sesuai dan ikuti
                            penilaian bersama asesor.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- CTA Button --}}
        <div class="w-full fade-up delay-3">
            <a href="{{ config('app.url') }}"
                class="block w-full text-center bg-white text-[#1e8a3c] font-extrabold text-base py-4 rounded-2xl shadow-lg active:scale-[0.97] transition-transform">
                Mulai Proses Sertifikasi
            </a>
        </div>

        {{-- Footer --}}
        <p class="fade-up delay-4 text-white/40 text-[11px] font-semibold uppercase tracking-widest text-center">
            &copy; {{ date('Y') }} LSP Mitra Karya Cemerlang
        </p>

    </div>

</body>

</html>