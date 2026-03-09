<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\APL02Controller;

// Route::get('/active', function () {
//     return view('auth.activation');
// })->name('verif');

// Route::get('/reset', function () {
//     return view('auth.reset');
// })->name('reset');

// Route::get('/', function () {
//     return view('welcome');
// })->name('login');

// Route::post('/login', [AuthController::class, 'login']);

// // Route Grup ini buat semua yang hanya bisa diakses sama asesor maupun admin
// Route::middleware(['auth', 'is_admin:admin,asesor'])->group(function () {
//     Route::get('/admin', function () {
//         return view('pages.dashboard'); // Ganti ke admin dashboard
//     })->name('admin');

//     // Route::get('/dashboard', function () {
//     //     return view('pages.dashboard');
//     // })->name('dashboard');

Route::get('/logout', [AuthController::class, 'logout']);

// Form
Route::get('/apl01', [APL01Controller::class, 'index'])->name('apl01.form');
Route::post('/apl01/generate', [APL01Controller::class, 'generate'])->name('apl01.generate');
Route::post('/apl02/generate', [APL02Controller::class, 'generate'])->name('apl02.generate');

Route::get('/apl01/preview', function () {
    $data = [
        'no_registrasi' => '',
        'tanggal' => '',
        'nama' => '',
        'nik' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'jenis_kelamin' => '',
        'agama' => '',
        'kebangsaan' => '',
        'no_telp' => '',
        'email' => '',
        'alamat' => '',
        'rt_rw' => '',
        'kelurahan' => '',
        'kecamatan' => '',
        'kabupaten' => '',
        'provinsi' => '',
        'kode_pos' => '',
        'pendidikan' => '',
        'pekerjaan' => '',
        'instansi' => '',
        'alamat_instansi' => '',
        'nama_skema' => '',
        'kode_skema' => '',
        'tujuan_asesmen' => '',
        'unit_kompetensi' => [
            ['kode' => 'M.741000.004.01', 'judul' => 'Melakukan survey sumber bahan baku'],
            ['kode' => 'M.741000.006.01', 'judul' => 'Menentukan jenis produk'],
            ['kode' => 'M.741000.008.01', 'judul' => 'Melakukan pengurusan perijinan usaha industri'],
            ['kode' => 'M.741000.014.01', 'judul' => 'Membuat jadwal kerja personil bagian produksi'],
            ['kode' => 'M.741000.019.01', 'judul' => 'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara dan produk akhir'],
            ['kode' => 'M.741000.026.01', 'judul' => 'Menjalin hubungan dengan pelanggan'],
            ['kode' => 'M.741000.027.01', 'judul' => 'Melakukan pembukan keuangan untuk setiap transaksi'],
            ['kode' => 'N.821100.001.02', 'judul' => 'Menangani Penerimaan dan Pengiriman Dokumen/Surat'],
            ['kode' => 'N.821100.067.01', 'judul' => 'Melakukan Transaksi Perbankan Sederhana'],
        ],
        'persyaratan_dasar' => [],
        'bukti_administratif' => [],
        'status_terima' => 'Diterima'
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.apl01', $data)->setPaper('a4', 'portrait');
    return $pdf->stream('preview.pdf');
});

// Preview (GET) — untuk testing tanpa form
Route::get('/apl02/preview', function () {
    $data = [
        'nama_skema' => 'Ahli Desain Grafis',
        'kode_skema' => 'SUK-SKS-REV1-L007',
        'nama_asesi' => '',
        'nama_asesor' => '',
        'no_reg_asesor' => '',
        'unit_kompetensi' => [
            [
                'no' => 1,
                'kode' => 'M.74100.001.02',
                'judul' => 'Mengaplikasikan Prinsip Dasar Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Menerapkan materi informasi yang berkaitan dengan pengetahuan dasar desain',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Sumber informasi ditentukan agar keabsahan informasi mengenai pengetahuan dasar desain sesuai dengan kebutuhan.'],
                            ['no' => '1.2', 'uraian' => 'Materi informasi dijelaskan secara sistematis sesuai dengan tahapan kerja desain.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Menjelaskan pengetahuan dasar seni rupa',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Definisi seni rupa dijelaskan secara teoritis dan melalui contoh karya.'],
                            ['no' => '2.2', 'uraian' => 'Elemen dasar yang terkandung dalam seni rupa diuraikan sesuai karakter dan penggunaannya masing-masing.'],
                            ['no' => '2.3', 'uraian' => 'Prinsip-prinsip dasar yang digunakan dalam pengolahan elemen desain/seni rupa dikaitkan sesuai dengan kebutuhan desain.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Menjelaskan pengertian dasar desain sebagai bagian dari lingkup seni rupa',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Perbedaan seni dan desain dijelaskan secara teoritis dan melalui contoh karya.'],
                            ['no' => '3.2', 'uraian' => 'Definisi Desain Grafis/DKV dibedakan dengan bidang desain lainnya.'],
                            ['no' => '3.3', 'uraian' => 'Fungsi utama dari Desain Grafis/DKV diuraikan secara sistematis.'],
                            ['no' => '3.4', 'uraian' => 'Prinsip dasar Desain Grafis/DKV dikaitkan sesuai dengan kebutuhan desain.'],
                        ]
                    ],
                    [
                        'no' => 4,
                        'nama' => 'Menerapkan pengetahuan sejarah desain',
                        'kriteria' => [
                            ['no' => '4.1', 'uraian' => 'Pengetahuan sejarah seni rupa dan desain dijelaskan secara verbal dan melalui visual.'],
                            ['no' => '4.2', 'uraian' => 'Ragam gaya desain diaplikasikan sesuai Prinsip Dasar Desain.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 2,
                'kode' => 'M.74100.002.02',
                'judul' => 'Menerapkan Prinsip Dasar Komunikasi',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Menggali materi informasi yang berkaitan dengan pengetahuan dasar komunikasi',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Sumber informasi ditelusuri agar keabsahan informasi mengenai pengetahuan dasar komunikasi sesuai dengan kebutuhan.'],
                            ['no' => '1.2', 'uraian' => 'Materi informasi ditunjukkan secara sistematis sesuai dengan proses komunikasi.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Menjelaskan pengetahuan dasar komunikasi',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Definisi dan prinsip dasar komunikasi diterangkan secara teoritis.'],
                            ['no' => '2.2', 'uraian' => 'Fungsi komunikasi diuraikan secara sistematis.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Membedakan komponen komunikasi',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Komponen komunikator dari proses komunikasi dijelaskan secara teoritis.'],
                            ['no' => '3.2', 'uraian' => 'Komponen pesan dari proses komunikasi dijelaskan secara teoritis.'],
                            ['no' => '3.3', 'uraian' => 'Komponen media dari proses komunikasi dijelaskan secara teoritis sesuai jenis-jenisnya.'],
                            ['no' => '3.4', 'uraian' => 'Komponen komunikan dalam proses komunikasi dijelaskan secara teoritis.'],
                            ['no' => '3.5', 'uraian' => 'Komponen pengaruh proses komunikasi dijelaskan secara teoritis.'],
                        ]
                    ],
                    [
                        'no' => 4,
                        'nama' => 'Menerapkan pengetahuan dasar komunikasi visual',
                        'kriteria' => [
                            ['no' => '4.1', 'uraian' => 'Definisi dan prinsip dasar komunikasi visual dijelaskan secara teoritis.'],
                            ['no' => '4.2', 'uraian' => 'Proses dan cara komunikasi visual dilaksanakan sesuai tahapan.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 3,
                'kode' => 'M.74100.003.02',
                'judul' => 'Menerapkan Pengetahuan Produksi Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Melaksanakan prinsip dan proses pra-produksi desain',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Prinsip-prinsip dalam proses pra-produksi desain diterapkan sesuai tahapan.'],
                            ['no' => '1.2', 'uraian' => 'Proses pra-produksi desain dikerjakan secara lengkap hingga karya komunikasi visual siap produksi.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Melaksanakan prinsip dan proses produksi desain',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Jenis-jenis produksi dan prinsip serta kualitas hasil dari masing-masing teknik produksi desain diuraikan sesuai jenis dan tahapannya.'],
                            ['no' => '2.2', 'uraian' => 'Jenis-jenis peralatan produksi dalam proses produksi desain dipakai sesuai kebutuhan.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Melaksanakan prinsip dan proses pasca produksi desain',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Jenis-jenis pasca produksi desain dan prinsip dari masing-masing teknik pasca produksi desain diuraikan sesuai jenis dan tahapannya.'],
                            ['no' => '3.2', 'uraian' => 'Teknik pasca produksi desain dipilih sesuai karakter dan kebutuhan.'],
                        ]
                    ],
                    [
                        'no' => 4,
                        'nama' => 'Mengelola pelaksanaan seluruh tahap produksi desain',
                        'kriteria' => [
                            ['no' => '4.1', 'uraian' => 'Seluruh proses pra produksi, produksi hingga pasca produksi desain dilaksanakan sesuai kebutuhan dan tujuan desain.'],
                            ['no' => '4.2', 'uraian' => 'Seluruh kegiatan proses produksi desain dan kemajuan pelaksanaan produksi didokumentasikan sesuai kebutuhan sebuah laporan.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 4,
                'kode' => 'M.74100.004.02',
                'judul' => 'Menerapkan Project Brief',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Memahami project brief',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Data-data perusahaan/klien diuraikan secara verbal dan visual.'],
                            ['no' => '1.2', 'uraian' => 'Permasalahan klien yang tercantum dalam project brief dirangkum sesuai kebutuhan.'],
                            ['no' => '1.3', 'uraian' => 'Informasi anggaran yang dimiliki oleh klien dirumuskan sesuai kebutuhan.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Melaksanakan project brief',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Tujuan proyek ditentukan sesuai permasalahan klien.'],
                            ['no' => '2.2', 'uraian' => 'Ruang lingkup proyek disusun sesuai kebutuhan klien.'],
                            ['no' => '2.3', 'uraian' => 'Waktu pengerjaan proyek disusun sesuai tahapan kerja.'],
                            ['no' => '2.4', 'uraian' => 'Anggaran proyek diatur sesuai jadwal dan tahapan kerja.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 5,
                'kode' => 'M.74100.005.02',
                'judul' => 'Menerapkan Design Brief',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Menyusun design brief',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Design brief untuk pengembangan konsep desain disusun berdasarkan project brief. Design brief untuk pihak ketiga disusun berdasarkan project brief.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Melaksanakan design brief',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Proyek desain diuraikan secara komprehensif.'],
                            ['no' => '2.2', 'uraian' => 'Tujuan desain dinyatakan sesuai project brief.'],
                            ['no' => '2.3', 'uraian' => 'Ruang lingkup desain disimpulkan berdasarkan project brief.'],
                            ['no' => '2.4', 'uraian' => 'Khalayak sasaran ditentukan sesuai project brief.'],
                            ['no' => '2.5', 'uraian' => 'Media desain ditentukan sesuai tujuan desain.'],
                            ['no' => '2.6', 'uraian' => 'Waktu pengerjaan desain dilaksanakan sesuai jadwal.'],
                            ['no' => '2.7', 'uraian' => 'Pembagian tugas dan tanggung jawab dikerjakan sesuai proyek desain.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 6,
                'kode' => 'M.74100.006.01',
                'judul' => 'Mengorganisasi Informasi Terkait Proyek Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Mempersiapkan informasi',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Metode pengumpulan informasi dipilih sesuai kebutuhan.'],
                            ['no' => '1.2', 'uraian' => 'Informasi terkait design brief diorganisir diolah secara sistematis.'],
                            ['no' => '1.3', 'uraian' => 'Informasi terkait design brief disusun berdasarkan metode analisis.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Menganalisis informasi',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Metode analisis informasi dijelaskan secara teoritis.'],
                            ['no' => '2.2', 'uraian' => 'Metode analisis informasi dipilih sesuai kebutuhan dalam design brief.'],
                            ['no' => '2.3', 'uraian' => 'Informasi disusun sesuai kebutuhan pembuatan rekomendasi.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Merumuskan informasi kunci',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Rekomendasi informasi dibuat sesuai kebutuhan untuk pemahaman proyek desain.'],
                            ['no' => '3.2', 'uraian' => 'Rekomendasi informasi dirumuskan sebagai informasi kunci sesuai kebutuhan dasar pengembangan strategi dan konsep desain.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 7,
                'kode' => 'M.74100.007.01',
                'judul' => 'Menetapkan Strategi Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Merencanakan strategi desain',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Strategi desain dipilih sesuai design brief.'],
                            ['no' => '1.2', 'uraian' => 'Informasi kunci dikembangkan menjadi strategi desain berdasarkan project brief.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Menetapkan strategi desain',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Alternatif skenario strategi desain dipilih sesuai tujuan proyek desain.'],
                            ['no' => '2.2', 'uraian' => 'Strategi desain ditetapkan sebagai dasar konsep desain.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 8,
                'kode' => 'M.74100.008.02',
                'judul' => 'Menetapkan Konsep Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Mengembangkan informasi kunci',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Bahan informasi kunci dikelompokkan sesuai dengan konteks dan tujuan desain.'],
                            ['no' => '1.2', 'uraian' => 'Informasi kunci dikembangkan menjadi Gagasan.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Mengolah gagasan konsep desain',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Gagasan dikembangkan menjadi beberapa alternatif sesuai design brief.'],
                            ['no' => '2.2', 'uraian' => 'Alternatif gagasan divalidasi sesuai dengan tujuan desain.'],
                            ['no' => '2.3', 'uraian' => 'Gagasan terpilih disusun menjadi konsep desain.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Mengkomunikasikan konsep desain dalam tim internal',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Metode komunikasi yang paling sesuai dipilih berdasarkan tujuan desain.'],
                            ['no' => '3.2', 'uraian' => 'Aspek di dalam konsep desain dikembangkan sesuai dengan metode komunikasi terpilih.'],
                            ['no' => '3.3', 'uraian' => 'Konsep desain diuraikan sesuai dengan tujuan desain dalam tim internal.'],
                        ]
                    ],
                    [
                        'no' => 4,
                        'nama' => 'Menetapkan konsep desain dalam tim internal',
                        'kriteria' => [
                            ['no' => '4.1', 'uraian' => 'Umpan balik mengenai konsep desain oleh tim internal dihubungkan dengan tujuan desain.'],
                            ['no' => '4.2', 'uraian' => 'Konsep desain dirumuskan sesuai perkembangan konsep di tim internal.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 9,
                'kode' => 'M.74100.009.02',
                'judul' => 'Mengoperasikan Perangkat Lunak Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Memilih jenis perangkat lunak',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Perangkat lunak yang relevan digunakan dijelaskan sesuai jenis-jenis dan perbedaan utamanya.'],
                            ['no' => '1.2', 'uraian' => 'Manfaat, karakter, keunggulan dan kekurangan dari tiap jenis perangkat lunak yang digunakan diuraikan secara teoritis dan melalui contoh karya.'],
                            ['no' => '1.3', 'uraian' => 'Perangkat lunak dipilih sesuai kebutuhan.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Menetapkan perangkat lunak',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Fasilitas dalam perangkat lunak digunakan secara optimal sesuai fungsinya.'],
                            ['no' => '2.2', 'uraian' => 'Perangkat lunak yang sesuai dimanfaatkan untuk menghasilkan karya desain yang produktif dan hasil kerja yang optimal.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 10,
                'kode' => 'M.74100.010.01',
                'judul' => 'Menciptakan Karya Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Mengidentifikasi design brief atau project brief',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Kata kunci yang berkaitan dengan data-data awal dan kebutuhan mood/karakter visual ditetapkan melalui proses brainstorming.'],
                            ['no' => '1.2', 'uraian' => 'Konsep desain ditentukan berdasarkan kata-kata kunci.'],
                            ['no' => '1.3', 'uraian' => 'Karakter/mood visual dikembangkan sesuai arahan dari konsep desain.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Membuat sketsa karya desain',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Sketsa thumbnail dikembangkan sesuai konsep kreatif yang telah ditentukan.'],
                            ['no' => '2.2', 'uraian' => 'Sketsa kasar dimodifikasi sesuai dengan sketsa thumbnail.'],
                            ['no' => '2.3', 'uraian' => 'Sketsa berwarna dikembangkan sesuai dengan sketsa kasar yang telah disesuaikan dengan design brief.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Mengembangkan wujud karya desain',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Media karya desain ditentukan sesuai kebutuhan atau pesan yang ingin disampaikan.'],
                            ['no' => '3.2', 'uraian' => 'Elemen visualisasi karya desain ditentukan sesuai objektif pada project brief.'],
                            ['no' => '3.3', 'uraian' => 'Komposisi elemen visual/layout karya desain ditentukan sesuai dengan prinsip desain dan objektif.'],
                            ['no' => '3.4', 'uraian' => 'Alternatif/variasi karya desain dikembangkan agar melengkapi kemungkinan pencapaian konsep yang strategis.'],
                        ]
                    ],
                    [
                        'no' => 4,
                        'nama' => 'Mendiskusikan wujud karya desain',
                        'kriteria' => [
                            ['no' => '4.1', 'uraian' => 'Alternatif wujud karya desain didiskusikan kesesuaiannya dengan konsep desain.'],
                            ['no' => '4.2', 'uraian' => 'Karya desain yang akan dijadikan purwarupa/dummy ditentukan sesuai hasil diskusi wujud karya desain.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 11,
                'kode' => 'M.74100.011.02',
                'judul' => 'Mengevaluasi Hasil Karya Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Membandingkan karya-karya desain yang ada',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Keunggulan/keberhasilan masing-masing karya diidentifikasikan secara jelas dan selengkap-lengkapnya berdasarkan pengetahuan desain dan referensi terkait.'],
                            ['no' => '1.2', 'uraian' => 'Kelemahan/kekurangan masing-masing karya diidentifikasikan secara jelas dan selengkap-lengkapnya berdasarkan pengetahuan desain dan referensi terkait.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Memilih diantara karya-karya desain yang ada',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Hasil perbandingan desain secara obyektif ditimbang kembali untuk menemukan solusi yang paling sesuai dengan design brief.'],
                            ['no' => '2.2', 'uraian' => 'Hasil pertimbangan dikonfirmasi kembali sesuai project brief.'],
                            ['no' => '2.3', 'uraian' => 'Karya yang memenuhi kriteria design brief dipersiapkan untuk dipresentasikan.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 12,
                'kode' => 'M.74100.012.02',
                'judul' => 'Mempresentasikan Karya Desain',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Menggunakan teori desain grafis dalam memaparkan atau mengidentifikasi konsep desain',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Pendekatan kreatif serta segala proses dalam pencapaian hasil akhir desain disajikan secara proporsional dan pada tempatnya dan melalui progres yang berkesinambungan.'],
                            ['no' => '1.2', 'uraian' => 'Penggunaan media dalam karya rancangan dijelaskan sesuai dengan konsep desain.'],
                            ['no' => '1.3', 'uraian' => 'Penggunaan layout berikut elemennya dalam karya rancangan dijelaskan sesuai dengan konsep desain.'],
                            ['no' => '1.4', 'uraian' => 'Penggunaan tipografi dalam karya rancangan dijelaskan sesuai konsep desain.'],
                            ['no' => '1.5', 'uraian' => 'Penggunaan warna dalam karya rancangan dijelaskan sesuai dengan konsep desain.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Menggunakan teori komunikasi untuk mengidentifikasi komponen karya yang dipresentasikan',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Subyek penyampai pesan/klien beserta kompetitornya diidentifikasi sesuai konsep desain.'],
                            ['no' => '2.2', 'uraian' => 'Pesan-pesan melalui karya desain diuraikan secara rasional sesuai konsep desain.'],
                            ['no' => '2.3', 'uraian' => 'Target atau sasaran komunikasi dijabarkan sesuai konsep desain.'],
                        ]
                    ],
                    [
                        'no' => 3,
                        'nama' => 'Menjabarkan tahapan-tahapan desain secara menyeluruh kepada klien',
                        'kriteria' => [
                            ['no' => '3.1', 'uraian' => 'Permasalahan dijelaskan secara lengkap.'],
                            ['no' => '3.2', 'uraian' => 'Konsep solusi kreatif dijelaskan secara obyektif dan detil.'],
                            ['no' => '3.3', 'uraian' => 'Eksekusi desain akhir sesuai konsep awal dijelaskan secara sistematis.'],
                        ]
                    ],
                    [
                        'no' => 4,
                        'nama' => 'Menampilkan presentasi visual',
                        'kriteria' => [
                            ['no' => '4.1', 'uraian' => 'Dokumentasi serta tahapan dari pengembangan dan hasil karya desain ditampilkan secara tertulis maupun lisan.'],
                            ['no' => '4.2', 'uraian' => 'Dummy atau purwarupa dipresentasikan dengan penjelasan secara lugas kepada klien.'],
                        ]
                    ],
                ]
            ],
            [
                'no' => 13,
                'kode' => 'M.74100.013.02',
                'judul' => 'Membuat Materi Siap Produksi',
                'elemen' => [
                    [
                        'no' => 1,
                        'nama' => 'Menerjemahkan desain akhir menjadi materi siap produksi',
                        'kriteria' => [
                            ['no' => '1.1', 'uraian' => 'Format materi siap produksi ditentukan sesuai dengan kebutuhan produksi.'],
                            ['no' => '1.2', 'uraian' => 'Materi siap produksi dibuat dengan menggunakan teknik dan cara yang sesuai dengan kebutuhan produksi.'],
                        ]
                    ],
                    [
                        'no' => 2,
                        'nama' => 'Memastikan materi siap produksi',
                        'kriteria' => [
                            ['no' => '2.1', 'uraian' => 'Isi materi siap produksi diperiksa kembali agar seluruh informasi di dalamnya dan kualitas tampilannya sesuai dengan kebutuhan proyek.'],
                            ['no' => '2.2', 'uraian' => 'Materi siap produksi ditetapkan menjadi bersifat akhir sesuai persetujuan klien.'],
                        ]
                    ],
                ]
            ],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.apl02', $data)->setPaper('a4', 'portrait');
    return $pdf->stream('apl02-preview.pdf');
});
