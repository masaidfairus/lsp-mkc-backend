<div style="text-align: center;">
    Pekanbaru, {{ now()->translatedFormat('d F Y') }}
    <br><br><br>
    Kepada Yth.
    <br>
    Saudara {{ $user->name ?? 'Nama Asesi' }}
    <br><br><br>
    Terima kasih telah melakukan registrasi akun pada Aplikasi Pendaftaran Uji Kompetensi LSP MKC, dengan informasi akun
    sebagai berikut:
    <br><br><br>
    NIK/Paspor: {{ $user->identity_number ?? '-' }}
    <br>
    Email: {{ $user->email ?? '-' }}
    <br>
    Password: {{ $password ?? '-' }}
    <br><br><br>
    Untuk dapat menggunakan akun tersebut, silakan klik link berikut untuk mengaktifkan akun Anda:
    {{-- Action Button --}}
    @isset($actionText)
        <?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
            ?>
        <x-mail::button :url="$actionUrl" :color="$color">
            {{ $actionText }}
        </x-mail::button>
    @endisset

    @isset($actionText)
        <x-slot:subcopy>
            @lang(
        "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" .
        'into your web browser:',
        [
            'actionText' => $actionText,
        ]
    ) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        </x-slot:subcopy>
    @endisset

    Jika tombol di atas tidak berfungsi, silakan salin dan tempel link berikut ke browser Anda: {{ $actionUrl }}

    <br><br>
    PENTING:
    <br>
    - Link aktivasi hanya berlaku selama 1x24 jam.
    <br>
    - Disarankan agar pemohon mengganti password setelah login.
    <br><br><br>

    Salam,
    <br>
    LSP MKC
    <br><br>
    ===========================================<br>
    Ini adalah email otomatis. Mohon tidak membalas email ini.
</div>