<?php

// Baca keterangan dibawah untuk membuat error kustom.
// CATATAN:
// E100x	Auth & Login
// E200x	Registrasi & Identitas
// E300x	Aktivasi Email
// E400x	Validasi Request
// E500x	Server / Sistem

return [

    'E1001' => 'User tidak ditemukan',
    'E1002' => 'Password salah',
    'E1003' => 'Akun belum diaktivasi',
    'E1004' => 'Akun admin tidak terdaftar',

    'E2001' => 'Email sudah terdaftar',
    'E2002' => 'NIK/Paspor sudah terdaftar',
    'E2003' => 'Data registrasi tidak lengkap',
    'E2004' => 'Format ID tidak valid',
    'E2005' => 'Format Paspor tidak valid',
    'E2006' => 'Data Pemohon belum dicantumkan',
    'E2007' => 'Data sertifikasi belum dicantumkan',
    'E2008' => 'Skema tidak tercantum di data anda',

    'E3001' => 'Link verifikasi tidak valid atau kadaluarsa',
    'E3002' => 'Link verifikasi sudah digunakan',
    'E3003' => 'Kode OTP tidak valid atau kadaluarsa',
    'E3004' => 'Token tidak valid atau kadaluarsa',

    'E4001' => 'Method tidak diizinkan',
    'E4002' => 'Payload tidak valid',
    'E4003' => 'Endpoint tidak ditemukan',

    'E5001' => 'Kesalahan database',
    'E5002' => 'Gagal mengirim email',
    'E5003' => 'Kesalahan internal server',

];
