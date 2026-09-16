<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Pengaduan & Bantuan - LSP MKC</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #009447;
            --primary-light: #00b356;
            --danger-color: #e3342f;
            --text-main: #333333;
            --text-muted: #666666;
            --bg-body: #f4f7f6;
            --bg-card: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            padding: 80px 20px 60px;
            text-align: center;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 148, 71, 0.2);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.8s ease-out;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            transform: rotate(30deg);
            pointer-events: none;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Main Content */
        .container {
            max-width: 850px;
            margin: -30px auto 50px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .card {
            background-color: var(--bg-card);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.04);
            animation: slideUp 0.8s ease-out 0.2s both;
            margin-bottom: 20px;
        }

        h2 {
            color: var(--primary-color);
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 35px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 24px;
            background-color: var(--primary-color);
            border-radius: 4px;
        }

        h2.danger::before {
            background-color: var(--danger-color);
        }
        h2.danger {
            color: var(--danger-color);
        }

        h2:first-of-type {
            margin-top: 0;
        }

        p {
            margin-bottom: 15px;
            color: var(--text-muted);
            font-size: 1rem;
        }
        
        .contact-box {
            background: #f9fbf9;
            border: 1px solid #e2e8e4;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .contact-box h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--text-main);
        }

        ul {
            list-style-type: none;
            margin-bottom: 20px;
        }

        ul li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 10px;
            color: var(--text-muted);
        }

        ul li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--primary-color);
            font-weight: bold;
            background: rgba(0, 148, 71, 0.1);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        strong {
            color: var(--text-main);
            font-weight: 600;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background-color: var(--primary-light);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #cc2a25;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 30px;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 600px) {
            .hero {
                padding: 60px 20px 40px;
                border-bottom-left-radius: 20px;
                border-bottom-right-radius: 20px;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .card {
                padding: 25px;
                border-radius: 12px;
            }
            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <header class="hero">
        <h1>Pusat Pengaduan & Bantuan</h1>
        <p>Layanan informasi, pengaduan keluhan, dan permohonan data bagi pengguna aplikasi LSP MKC.</p>
    </header>

    <main class="container">
        <div class="card">
            <h2>Pengaduan & Pelaporan</h2>
            <p>Jika Anda mengalami kendala terkait jadwal asesmen, hasil sertifikasi, keluhan terhadap Asesor, maupun masalah teknis pada aplikasi, Anda dapat menghubungi tim kami. Layanan ini terbuka bagi siapa saja (termasuk anonim) yang ingin memberikan masukan atau laporan.</p>
            
            <div class="contact-box">
                <h3>Kontak Admin / Sekretariat</h3>
                <p>Untuk pertanyaan umum, kendala teknis, dan informasi pendaftaran.</p>
                <p><strong>Email:</strong> admin@lspmkc.id</p>
                <p><strong>WhatsApp:</strong> +62 812-XXXX-XXXX (Ubah sesuai dengan nomor aktif)</p>
            </div>
            
            <div class="contact-box">
                <h3>Kontak Manajer Sertifikasi / Asesor</h3>
                <p>Khusus untuk pengaduan terkait teknis uji kompetensi atau banding hasil asesmen.</p>
                <p><strong>Email:</strong> sertifikasi@lspmkc.id</p>
            </div>
            
            <p>Harap sampaikan laporan Anda dengan detail yang jelas agar tim kami dapat segera menindaklanjuti.</p>
        </div>

        <div class="card">
            <h2 class="danger">Permintaan Penghapusan Akun & Data</h2>
            <p>Sesuai dengan Kebijakan Privasi kami dan persyaratan keamanan data dari Google Play, pengguna berhak mengajukan permohonan penghapusan akun serta seluruh data pribadi dari sistem kami.</p>
            
            <p><strong>Langkah-langkah penghapusan akun:</strong></p>
            <ul>
                <li>Kirimkan email permohonan ke <strong>admin@lspmkc.id</strong> dengan subjek "Permintaan Penghapusan Akun".</li>
                <li>Sertakan alamat email atau NIK yang terdaftar pada akun Anda untuk verifikasi identitas.</li>
                <li>Tim kami akan memverifikasi permohonan Anda dan menghapus semua data pribadi dari database kami dalam waktu maksimal 3x24 jam kerja.</li>
                <li>Jenis data yang dihapus meliputi: Profil pengguna, dokumen unggahan (KTP/Ijazah), riwayat asesmen, dan data akun secara keseluruhan.</li>
            </ul>
            
            <p><em>*Catatan: Penghapusan akun bersifat permanen dan tidak dapat dibatalkan. Beberapa data mungkin tetap dipertahankan hanya sebagai arsip pelaporan resmi sertifikasi kompetensi (jika Anda sudah tersertifikasi) sesuai regulasi BNSP yang berlaku.</em></p>
            
            <a href="mailto:admin@lspmkc.id?subject=Permintaan Penghapusan Akun" class="btn btn-danger">Ajukan Penghapusan Akun</a>
        </div>
    </main>

    <footer>
        &copy; 2026 LSP MKC. Hak Cipta Dilindungi Undang-Undang.
    </footer>

</body>
</html>
