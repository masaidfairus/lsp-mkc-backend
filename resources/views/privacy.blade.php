<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - LSP MKC</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #009447;
            --primary-light: #00b356;
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

        h2:first-of-type {
            margin-top: 0;
        }

        p {
            margin-bottom: 15px;
            color: var(--text-muted);
            font-size: 1rem;
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
        <h1>Kebijakan Privasi</h1>
        <p>Transparansi dan keamanan data Anda adalah prioritas utama di LSP MKC.</p>
    </header>

    <main class="container">
        <div class="card">
            <p><strong>Terakhir diperbarui:</strong> 16 Agustus 2026</p>
            <p>Kebijakan Privasi ini menjelaskan bagaimana <strong>LSP MKC</strong> ("kami", "milik kami", atau "kita") mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat Anda menggunakan aplikasi seluler dan layanan kami. Desain dan ketentuan ini dibuat selaras dengan pengalaman modern dan aman pada platform kami.</p>

            <h2>1. Informasi yang Kami Kumpulkan</h2>
            <p>Saat Anda menggunakan Aplikasi kami, kami mungkin meminta Anda untuk memberikan informasi pengenal pribadi tertentu yang relevan untuk proses sertifikasi. Informasi tersebut termasuk, namun tidak terbatas pada:</p>
            <ul>
                <li>Nama lengkap dan Identitas Resmi (KTP/Passport)</li>
                <li>Alamat Email dan Nomor Telepon</li>
                <li>Data terkait pendaftaran skema sertifikasi kompetensi</li>
                <li>Dokumen portofolio, ijazah, atau berkas yang diunggah untuk keperluan asesmen</li>
                <li>Tanda tangan digital untuk keperluan pengesahan form (misal: APL-01, APL-02)</li>
            </ul>

            <h2>2. Bagaimana Kami Menggunakan Informasi Anda</h2>
            <p>Data Anda dikelola dengan standar keamanan tinggi dan digunakan murni untuk tujuan profesional berikut:</p>
            <ul>
                <li>Memfasilitasi pendaftaran, penjadwalan, dan pelaksanaan asesmen sertifikasi di LSP MKC</li>
                <li>Menerbitkan sertifikat kompetensi jika Anda dinyatakan kompeten</li>
                <li>Menyediakan pembaruan layanan, jadwal ujian, atau notifikasi penting langsung ke perangkat Anda</li>
                <li>Memenuhi kewajiban pelaporan resmi yang disyaratkan oleh BNSP (Badan Nasional Sertifikasi Profesi)</li>
            </ul>

            <h2>3. Berbagi Data dengan Pihak Ketiga</h2>
            <p>Kami sangat menjaga privasi Anda. Kami <strong>tidak akan pernah</strong> menjual atau menyewakan informasi pribadi Anda. Data Anda hanya akan dibagikan kepada pihak berwenang (seperti BNSP) semata-mata untuk legalitas sertifikasi dan pelaporan yang diwajibkan oleh regulasi negara.</p>

            <h2>4. Keamanan Data</h2>
            <p>Infrastruktur aplikasi kami dirancang dengan mempertimbangkan keamanan. Kami menerapkan enkripsi dan standar perlindungan data industri untuk mencegah akses, pengungkapan, atau modifikasi yang tidak sah terhadap Informasi Pribadi maupun dokumen portofolio Anda.</p>

            <h2>5. Hak Pengguna & Penghapusan Data</h2>
            <p>Anda memiliki hak untuk meminta akses, perbaikan, atau penghapusan data pribadi Anda dari sistem kami, selama hal tersebut tidak bertentangan dengan kewajiban penyimpanan data arsip sertifikasi BNSP. Untuk permohonan tersebut, Anda dapat menghubungi tim support kami.</p>

            <h2>6. Perubahan Kebijakan</h2>
            <p>Kebijakan Privasi ini dapat diperbarui sewaktu-waktu seiring peningkatan layanan aplikasi kami. Setiap perubahan yang signifikan akan diberitahukan melalui notifikasi di dalam aplikasi atau melalui pembaruan di halaman ini.</p>

            <h2>7. Hubungi Kami</h2>
            <p>Jika Anda memiliki pertanyaan mengenai keamanan data atau Kebijakan Privasi ini, silakan hubungi tim administrasi LSP MKC melalui email atau pusat bantuan resmi kami di dalam aplikasi.</p>
        </div>
    </main>

    <footer>
        &copy; 2026 LSP MKC. Hak Cipta Dilindungi Undang-Undang.
    </footer>

</body>
</html>
