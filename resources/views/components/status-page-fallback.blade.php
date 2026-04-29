<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belum Dapat Dicetak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-sm overflow-hidden">

        <!-- Header Progress -->
        <div class="bg-white px-6 pt-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 text-xl">📄</div>
                    <div>
                        <p class="font-semibold text-lg">{{ str_replace(' Belum Siap', '', $title) }}</p>
                        <p class="text-sm text-gray-500">Status Dokumen</p>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="flex items-center gap-1 mb-8">
                <div class="h-2 flex-1 bg-green-500 rounded-full"></div>
                <div class="h-2 flex-1 bg-green-500 rounded-full"></div>
                <div class="h-2 flex-1 bg-gray-200 rounded-full"></div>
                <div class="h-2 flex-1 bg-gray-200 rounded-full"></div>
            </div>
        </div>

        <div class="px-6 pb-8">
            <div class="text-center">
                <div class="mx-auto w-20 h-20 bg-yellow-100 rounded-3xl flex items-center justify-center text-5xl mb-6">
                    ⏳
                </div>
                
                <h1 class="text-2xl font-semibold text-gray-800 mb-2">{{ $title }}</h1>
                <p class="text-gray-600 leading-relaxed">{{ $message }}</p>
            </div>

            <div class="mt-8 bg-gray-50 rounded-2xl p-5 text-sm">
                <p class="font-medium text-gray-700 mb-2">Info:</p>
                <ul class="text-gray-600 space-y-2">
                    <li class="flex gap-2">
                        <span class="text-green-500">•</span>
                        Proses verifikasi biasanya 1-2 hari kerja
                    </li>
                    <li class="flex gap-2">
                        <span class="text-green-500">•</span>
                        Kamu akan mendapat notifikasi setelah disetujui
                    </li>
                </ul>
            </div>

           <a href="{{ route('filament.admin.resources.assessment-registrations.edit', $registration) }}" 
   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-4 rounded-2xl mt-8 transition">
    Kembali ke Edit Registrasi
</a>
        </div>
    </div>

</body>
</html>