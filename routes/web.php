<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PrivateFileController;
use App\Http\Controllers\PDF\PdfAk01Controller;
use App\Http\Controllers\PDF\PdfAk02Controller;
use App\Http\Controllers\PDF\PdfAk03Controller;
use App\Http\Controllers\PDF\PdfAk04Controller;
use App\Http\Controllers\PDF\PdfAk05Controller;
use App\Http\Controllers\PDF\PdfAk06Controller;
use App\Http\Controllers\PDF\PdfAk07Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDF\PdfApl01Controller;
use App\Http\Controllers\PDF\PdfApl02Controller;
use App\Http\Controllers\PDF\PdfMapa01Controller;
use App\Http\Controllers\PDF\PdfMapa02Controller;

Route::get('/test-activation', function () {
    return view('auth.activation');
});

// Route::get('/active', function () {
//     return view('auth.activation');
// })->name('verif');

// Route::get('/', function () {
//     return view('welcome');
// })->name('login');

// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware(['auth', 'is_admin:admin,asesor'])->group(function () {
//     Route::get('/admin', function () {
//         return view('pages.dashboard');
//     })->name('admin');
//     Route::get('/logout', [AuthController::class, 'logout']);
// });

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/signature/verify', function (\Illuminate\Http\Request $request) {
    $id = $request->query('id');
    $sigData = null;

    if ($id) {
        // Coba cari di SignatureRequest dulu (untuk dokumen spesifik)
        $req = \App\Models\SignatureRequest::find($id);
        if ($req && $req->status === 'signed') {
            $sigData = [
                'n' => $req->user->name,
                'r' => $req->role,
                'd' => $req->document_label,
                't' => $req->signed_at->format('Y-m-d H:i:s'),
                's' => null, // Sesuai permintaan: sembunyikan bentuk TTD
                'v' => true,
            ];
        } else {
            // Fallback ke UserSignature (library lama)
            $sig = \App\Models\UserSignature::find($id);
            if ($sig) {
                $sigData = [
                    'n' => $sig->name,
                    'r' => 'Pemilik Tanda Tangan',
                    'd' => 'Library Tanda Tangan',
                    't' => $sig->updated_at->format('Y-m-d H:i:s'),
                    's' => null,
                    'v' => true,
                ];
            }
        }
    }

    return view('signature.verify', ['sigData' => $sigData]);
})->name('signature.verify');

Route::get('admin/view-document/{record}', [PrivateFileController::class, 'download'])
    ->name('private.file.view')
    ->middleware(['auth']);

// ============================================================
// PDF Documents (Auth Required for Preview)
// ============================================================
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('apl01/{registration}', [PdfApl01Controller::class, 'show'])->name('apl01.form');
    Route::get('apl02/{registration}', [PdfApl02Controller::class, 'show'])->name('apl02.form');
    Route::get('ak01/{registration}', [PdfAk01Controller::class, 'show'])->name('ak01.form');
    Route::get('ak02/{registration}', [PdfAk02Controller::class, 'show'])->name('ak02.form');
    Route::get('ak03/{registration}', [PdfAk03Controller::class, 'show'])->name('ak03.form');
    Route::get('ak04/{registration}', [PdfAk04Controller::class, 'show'])->name('ak04.form');
    Route::get('ak05/{registration}', [PdfAk05Controller::class, 'show'])->name('ak05.form');
    Route::get('ak06/{registration}', [PdfAk06Controller::class, 'show'])->name('ak06.form');
    Route::get('ak07/{registration}', [PdfAk07Controller::class, 'show'])->name('ak07.form');
    Route::get('ia01/{registration}', [\App\Http\Controllers\PDF\PdfIA01Controller::class, 'show'])->name('ia01.form');
    Route::get('ia02/{registration}', [\App\Http\Controllers\PDF\PdfIa02Controller::class, 'show'])->name('ia02.form');
    Route::get('ia03/{registration}', [\App\Http\Controllers\PDF\PdfIa03Controller::class, 'show'])->name('ia03.form');
    Route::get('ia04a/{registration}', [\App\Http\Controllers\PDF\PdfIa04aController::class, 'show'])->name('ia04a.form');
    Route::get('ia04b/{registration}', [\App\Http\Controllers\PDF\PdfIa04bController::class, 'show'])->name('ia04b.form');
    Route::get('ia05a/{registration}', [\App\Http\Controllers\PDF\PdfIa05aController::class, 'show'])->name('ia05a.form');
    Route::get('ia05b/{registration}', [\App\Http\Controllers\PDF\PdfIa05bController::class, 'show'])->name('ia05b.form');
    Route::get('ia05c/{registration}', [\App\Http\Controllers\PDF\PdfIa05cController::class, 'show'])->name('ia05c.form');
    Route::get('ia06a/{registration}', [\App\Http\Controllers\PDF\PdfIa06aController::class, 'show'])->name('ia06a.form');
    Route::get('ia06b/{registration}', [\App\Http\Controllers\PDF\PdfIa06bController::class, 'show'])->name('ia06b.form');
    Route::get('ia06c/{registration}', [\App\Http\Controllers\PDF\PdfIa06cController::class, 'show'])->name('ia06c.form');
    Route::get('ia07/{registration}', [\App\Http\Controllers\PDF\PdfIa07Controller::class, 'show'])->name('ia07.form');
    Route::get('ia08/{registration}', [\App\Http\Controllers\PDF\PdfIa08Controller::class, 'show'])->name('ia08.form');
    Route::get('ia09/{registration}', [\App\Http\Controllers\PDF\PdfIa09Controller::class, 'show'])->name('ia09.form');
    Route::get('ia10/{registration}', [\App\Http\Controllers\PDF\PdfIa10Controller::class, 'show'])->name('ia10.form');
    Route::get('ia11/{registration}', [\App\Http\Controllers\PDF\PdfIa11Controller::class, 'show'])->name('ia11.form');
    Route::get('va/{registration}', [\App\Http\Controllers\PDF\PdfVaController::class, 'show'])->name('frva.print');
    Route::get('mapa01/{registration}', [\App\Http\Controllers\PDF\PdfMapa01Controller::class, 'show'])->name('mapa01.form');
    Route::get('mapa02/{registration}', [\App\Http\Controllers\PDF\PdfMapa02Controller::class, 'show'])->name('mapa02.form');
});

Route::get('cek-ttd/{id}', [\App\Http\Controllers\SignatureVerificationController::class, 'show'])->name('ttd.cek');

Route::get('/mapa02/preview', function () {
    $data = [
        'nama_skema' => 'AHLI DESAIN GRAFIS',
        'kode_skema' => 'SUK-SKS-REV1-L007',

        'kelompok_pekerjaan' => [
            [
                'no' => 1,
                'nama' => 'Menerapkan pengetahuan tentang desain',
                'unit' => [
                    ['kode' => 'M.74100.001.02', 'judul' => 'Mengaplikasikan Prinsip Dasar Desain'],
                    ['kode' => 'M.74100.002.02', 'judul' => 'Menerapkan Prinsip Dasar Komunikasi'],
                    ['kode' => 'M.74100.003.02', 'judul' => 'Menerapkan Pengetahuan Produksi Desain'],
                ],
            ],
            [
                'no' => 2,
                'nama' => 'Menentukan obyektif dari program desain',
                'unit' => [
                    ['kode' => 'M.74100.004.02', 'judul' => 'Menerapkan Project Brief'],
                    ['kode' => 'M.74100.005.02', 'judul' => 'Menerapkan Design Brief'],
                    ['kode' => 'M.74100.006.01', 'judul' => 'Mengorganisasikan Informasi Terkait Proyek Desain'],
                ],
            ],
            [
                'no' => 3,
                'nama' => 'Menerapkan proses desain',
                'unit' => [
                    ['kode' => 'M.74100.007.01', 'judul' => 'Menetapkan Strategi Desain'],
                    ['kode' => 'M.74100.008.02', 'judul' => 'Menetapkan Konsep Desain'],
                    ['kode' => 'M.74100.009.02', 'judul' => 'Mengoperasikan Perangkat Lunak Desain'],
                    ['kode' => 'M.74100.010.01', 'judul' => 'Menciptakan Karya Desain'],
                    ['kode' => 'M.74100.011.02', 'judul' => 'Mengevaluasi Hasil Karya Desain'],
                    ['kode' => 'M.74100.012.02', 'judul' => 'Mempresentasikan Karya Desain'],
                ],
            ],
            [
                'no' => 4,
                'nama' => 'Menerapkan proses produksi',
                'unit' => [
                    ['kode' => 'M.74100.013.02', 'judul' => 'Membuat Materi Siap Produksi'],
                ],
            ],
        ],

        'penyusun_nama' => '',
        'penyusun_no_reg' => '',
        'validator_nama' => '',
        'validator_no_reg' => '',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.mapa.mapa02', $data)
        ->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true);

    return $pdf->stream('mapa02_preview.pdf');
});

Route::get('/invoice/preview', function () {
    $data = [
        'no_surat' => 'No Surat Disini',
        'tanggal_surat' => '08 Maret 2025',
        'nama_asesi' => '',
        'nik' => '',
        'no_reg' => '',
        'alamat' => '',
        'nama_skema' => '',
        'kode_skema' => '',

        'items' => [
            [
                'uraian'      => 'Biaya Sertifikasi',
                'keterangan'  => '',
                'qty'         => 1,
                'harga'       => 150000,
            ],
        ],

        'diskon' => 0,
        'ppn_persen' => 0, // isi 11 kalau kena PPN

        // Info LSP
        'lsp_nama' => 'INVOICE SERTIFIKASI LSP',
        'lsp_alamat' => 'Jl. Lorem Ipsum Dolor Sit',
        'lsp_telp' => '0888-8888-8888',
        'lsp_email' => 'lsp@gmail.com',

        // Rekening
        'rek_bank' => 'BNI',
        'rek_no' => '1234567890',
        'rek_atas_nama' => 'LSP Mitra Karya Cemerlang',

        // TTD
        'kota' => 'Jakarta',
        'nama_ttd' => 'Lorem Ipsum',
        'jabatan_ttd' => 'Manajer Sertifikasi',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.invoice', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('invoice_preview.pdf');
});

Route::get('/ia01/preview', function () {
    $data = [
        'nama_skema' => 'AHLI DESAIN GRAFIS',
        'kode_skema' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal' => '',

        'kelompok_pekerjaan' => [

            // ─── KP 1 ───────────────────────────────────────────
            [
                'no' => 1,
                'nama' => 'Menerapkan pengetahuan tentang desain',
                'unit_offset' => 0,
                'unit' => [
                    [
                        'kode' => 'M.74100.001.02',
                        'judul' => 'Mengaplikasikan Prinsip Dasar Desain',
                        'elemen' => [
                            [
                                'nama' => 'Menerapkan materi informasi yang berkaitan dengan pengetahuan dasar desain',
                                'kuk' => [
                                    'Sumber informasi ditentukan agar keabsahan informasi mengenai pengetahuan dasar desain sesuai dengan kebutuhan.',
                                    'Materi informasi dijelaskan secara sistematis sesuai dengan tahapan kerja desain.',
                                ]
                            ],
                            [
                                'nama' => 'Menjelaskan pengetahuan dasar seni rupa',
                                'kuk' => [
                                    'Definisi seni rupa dijelaskan secara teoritis dan melalui contoh karya.',
                                    'Elemen dasar yang terkandung dalam seni rupa diuraikan sesuai karakter dan penggunaannya masing-masing.',
                                    'Prinsip-prinsip dasar yang digunakan dalam pengolahan elemen desain/seni rupa dikaitkan sesuai dengan kebutuhan desain.',
                                ]
                            ],
                            [
                                'nama' => 'Menjelaskan pengertian dasar desain sebagai bagian dari lingkup seni rupa',
                                'kuk' => [
                                    'Perbedaan seni dan desain dijelaskan secara teoritis dan melalui contoh karya.',
                                    'Definisi Desain Grafis/DKV dibedakan dengan bidang desain lainnya.',
                                    'Fungsi utama dari Desain Grafis/DKV diuraikan secara sistematis.',
                                    'Prinsip dasar Desain Grafis/DKV dikaitkan sesuai dengan kebutuhan desain.',
                                ]
                            ],
                            [
                                'nama' => 'Mengaplikasikan pengetahuan mengenai sejarah seni rupa dan desain',
                                'kuk' => [
                                    'Pengetahuan sejarah seni rupa dan desain dijelaskan secara verbal dan visual.',
                                    'Ragam gaya desain diaplikasikan sesuai Prinsip Dasar Desain.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.002.02',
                        'judul' => 'Menerapkan Prinsip Dasar Komunikasi',
                        'elemen' => [
                            [
                                'nama' => 'Menggali materi informasi yang berkaitan dengan pengetahuan dasar komunikasi',
                                'kuk' => [
                                    'Sumber informasi ditelusuri agar keabsahan informasi mengenai pengetahuan dasar komunikasi sesuai dengan kebutuhan.',
                                    'Materi informasi ditunjukkan secara sistematis sesuai dengan proses komunikasi.',
                                ]
                            ],
                            [
                                'nama' => 'Menjelaskan pengetahuan dasar komunikasi',
                                'kuk' => [
                                    'Definisi dan prinsip dasar komunikasi diterangkan secara teoritis.',
                                    'Fungsi komunikasi diuraikan secara sistematis.',
                                    'Komponen komunikator dari proses komunikasi dijelaskan secara teoritis.',
                                ]
                            ],
                            [
                                'nama' => 'Membedakan komponen komunikasi',
                                'kuk' => [
                                    'Komponen pesan dari proses komunikasi dijelaskan secara teoritis.',
                                    'Komponen media dari proses komunikasi dijelaskan secara teoritis sesuai jenis-jenisnya.',
                                    'Komponen komunikan dalam proses komunikasi dijelaskan secara teoritis.',
                                    'Komponen pengaruh proses komunikasi dijelaskan secara teoritis.',
                                ]
                            ],
                            [
                                'nama' => 'Menerapkan pengetahuan dasar komunikasi visual',
                                'kuk' => [
                                    'Definisi dan prinsip dasar komunikasi visual dijelaskan secara teoritis.',
                                    'Proses dan cara komunikasi visual dilaksanakan sesuai tahapan.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.003.02',
                        'judul' => 'Menerapkan Pengetahuan Produksi Desain',
                        'elemen' => [
                            [
                                'nama' => 'Melaksanakan prinsip dan proses pra-produksi desain',
                                'kuk' => [
                                    'Prinsip-prinsip dalam proses pra-produksi desain diterapkan sesuai tahapan.',
                                    'Proses pra-produksi desain dikerjakan secara lengkap hingga karya komunikasi visual siap produksi.',
                                ]
                            ],
                            [
                                'nama' => 'Melaksanakan prinsip dan proses produksi desain',
                                'kuk' => [
                                    'Jenis-jenis produksi dan prinsip serta kualitas hasil dari masing-masing teknik produksi desain diuraikan sesuai jenis dan tahapannya.',
                                    'Jenis-jenis peralatan produksi dalam proses produksi desain dipakai sesuai kebutuhan.',
                                ]
                            ],
                            [
                                'nama' => 'Melaksanakan prinsip dan proses pasca produksi desain',
                                'kuk' => [
                                    'Jenis-jenis pasca produksi desain dan prinsip dari masing-masing teknik pasca produksi desain diuraikan sesuai jenis dan tahapannya.',
                                    'Teknik pasca produksi desain dipilih sesuai karakter dan kebutuhan.',
                                ]
                            ],
                            [
                                'nama' => 'Mengelola pelaksanaan seluruh tahap produksi desain',
                                'kuk' => [
                                    'Seluruh proses pra produksi, produksi hingga pasca produksi desain dilaksanakan sesuai kebutuhan dan tujuan desain.',
                                    'Seluruh kegiatan proses produksi desain dan kemajuan pelaksanaan produksi didokumentasikan sesuai kebutuhan sebuah laporan.',
                                ]
                            ],
                        ],
                    ],
                ],
            ],

            // ─── KP 2 ───────────────────────────────────────────
            [
                'no' => 2,
                'nama' => 'Menentukan obyektif dari program desain',
                'unit_offset' => 3,
                'unit' => [
                    [
                        'kode' => 'M.74100.004.02',
                        'judul' => 'Menerapkan Project Brief',
                        'elemen' => [
                            [
                                'nama' => 'Memahami project brief',
                                'kuk' => [
                                    'Data-data perusahaan/klien diuraikan secara verbal dan visual.',
                                    'Permasalahan klien yang tercantum dalam project brief dirangkum sesuai kebutuhan.',
                                    'Informasi anggaran yang dimiliki oleh klien dirumuskan sesuai kebutuhan.',
                                ]
                            ],
                            [
                                'nama' => 'Melaksanakan project brief',
                                'kuk' => [
                                    'Tujuan proyek ditentukan sesuai permasalahan klien.',
                                    'Ruang lingkup proyek disusun sesuai kebutuhan klien.',
                                    'Waktu pengerjaan proyek disusun sesuai tahapan kerja.',
                                    'Anggaran proyek diatur sesuai jadwal dan tahapan kerja.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.005.02',
                        'judul' => 'Menerapkan Design Brief',
                        'elemen' => [
                            [
                                'nama' => 'Menyusun design brief',
                                'kuk' => [
                                    'Design brief untuk pengembangan konsep desain disusun berdasarkan project brief.',
                                    'Design brief untuk pihak ketiga disusun berdasarkan project brief.',
                                ]
                            ],
                            [
                                'nama' => 'Melaksanakan design brief',
                                'kuk' => [
                                    'Proyek desain diuraikan secara komprehensif.',
                                    'Tujuan desain dinyatakan sesuai project brief.',
                                    'Ruang lingkup desain disimpulkan berdasarkan project brief.',
                                    'Khalayak sasaran ditentukan sesuai project brief.',
                                    'Media desain ditentukan sesuai tujuan desain.',
                                    'Waktu pengerjaan desain dilaksanakan sesuai jadwal.',
                                    'Pembagian tugas dan tanggung jawab dikerjakan sesuai proyek desain.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.006.01',
                        'judul' => 'Mengorganisasi Informasi Terkait Proyek Desain',
                        'elemen' => [
                            [
                                'nama' => 'Mempersiapkan informasi',
                                'kuk' => [
                                    'Metode pengumpulan informasi dipilih sesuai kebutuhan.',
                                    'Informasi terkait design brief diorganisir secara sistematis.',
                                    'Informasi terkait design brief disusun berdasarkan metode analisis.',
                                ]
                            ],
                            [
                                'nama' => 'Menganalisis informasi',
                                'kuk' => [
                                    'Metode analisis informasi dijelaskan secara teoritis.',
                                    'Metode analisis informasi dipilih sesuai kebutuhan dalam design brief.',
                                    'Informasi disusun sesuai kebutuhan pembuatan rekomendasi.',
                                ]
                            ],
                            [
                                'nama' => 'Merumuskan informasi kunci',
                                'kuk' => [
                                    'Rekomendasi informasi dibuat sesuai kebutuhan untuk pemahaman proyek desain.',
                                    'Rekomendasi informasi dirumuskan sebagai informasi kunci sesuai kebutuhan dasar pengembangan strategi dan konsep desain.',
                                ]
                            ],
                        ],
                    ],
                ],
            ],

            // ─── KP 3 ───────────────────────────────────────────
            [
                'no' => 3,
                'nama' => 'Menerapkan proses desain',
                'unit_offset' => 6,
                'unit' => [
                    [
                        'kode' => 'M.74100.007.01',
                        'judul' => 'Menetapkan Strategi Desain',
                        'elemen' => [
                            [
                                'nama' => 'Merencanakan strategi desain',
                                'kuk' => [
                                    'Strategi desain dipilih sesuai design brief.',
                                    'Informasi kunci dikembangkan menjadi strategi desain berdasarkan project brief.',
                                ]
                            ],
                            [
                                'nama' => 'Menetapkan strategi desain',
                                'kuk' => [
                                    'Alternatif skenario strategi desain dipilih sesuai tujuan proyek desain.',
                                    'Strategi desain ditetapkan sebagai dasar konsep desain.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.008.02',
                        'judul' => 'Menetapkan Konsep Desain',
                        'elemen' => [
                            [
                                'nama' => 'Mengembangkan informasi kunci',
                                'kuk' => [
                                    'Bahan informasi kunci dikelompokkan sesuai konteks dan tujuan desain.',
                                    'Informasi kunci dikembangkan menjadi Gagasan.',
                                ]
                            ],
                            [
                                'nama' => 'Mengolah gagasan konsep desain',
                                'kuk' => [
                                    'Gagasan dikembangkan menjadi beberapa alternatif sesuai design brief.',
                                    'Alternatif gagasan divalidasi sesuai dengan tujuan desain.',
                                    'Gagasan terpilih disusun menjadi konsep desain.',
                                ]
                            ],
                            [
                                'nama' => 'Mengkomunikasikan konsep desain dalam tim internal',
                                'kuk' => [
                                    'Metode komunikasi yang paling sesuai dipilih berdasarkan tujuan desain.',
                                    'Aspek di dalam konsep desain dikembangkan sesuai dengan metode komunikasi terpilih.',
                                    'Konsep desain diuraikan sesuai dengan tujuan desain dalam tim internal.',
                                ]
                            ],
                            [
                                'nama' => 'Menetapkan konsep desain dalam tim internal',
                                'kuk' => [
                                    'Umpan balik mengenai konsep desain oleh tim internal dihubungkan dengan tujuan desain.',
                                    'Konsep desain dirumuskan sesuai perkembangan konsep di tim internal.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.009.02',
                        'judul' => 'Mengoperasikan Perangkat Lunak Desain',
                        'elemen' => [
                            [
                                'nama' => 'Memilih jenis perangkat lunak',
                                'kuk' => [
                                    'Perangkat lunak yang relevan digunakan dijelaskan sesuai jenis-jenis dan perbedaan utamanya.',
                                    'Manfaat, karakter, keunggulan dan kekurangan dari tiap jenis perangkat lunak yang digunakan diuraikan secara teoritis dan melalui contoh karya.',
                                    'Perangkat lunak dipilih sesuai kebutuhan.',
                                ]
                            ],
                            [
                                'nama' => 'Menetapkan perangkat lunak',
                                'kuk' => [
                                    'Fasilitas dalam perangkat lunak digunakan secara optimal sesuai fungsinya.',
                                    'Perangkat lunak yang sesuai dimanfaatkan untuk menghasilkan karya desain yang produktif dan hasil kerja yang optimal.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.010.01',
                        'judul' => 'Menciptakan Karya Desain',
                        'elemen' => [
                            [
                                'nama' => 'Mengidentifikasi design brief atau project brief',
                                'kuk' => [
                                    'Kata kunci yang berkaitan dengan data-data awal dan kebutuhan mood/karakter visual ditetapkan melalui proses brainstorming.',
                                    'Konsep desain ditentukan berdasarkan kata-kata kunci.',
                                    'Karakter/mood visual dikembangkan sesuai arahan dari konsep desain.',
                                ]
                            ],
                            [
                                'nama' => 'Membuat sketsa karya desain',
                                'kuk' => [
                                    'Sketsa thumbnail dikembangkan sesuai konsep kreatif yang telah ditentukan.',
                                    'Sketsa kasar dimodifikasi sesuai dengan sketsa thumbnail.',
                                    'Sketsa berwarna dikembangkan sesuai dengan sketsa kasar yang telah disesuaikan dengan design brief.',
                                ]
                            ],
                            [
                                'nama' => 'Mengembangkan wujud karya desain',
                                'kuk' => [
                                    'Media karya desain ditentukan sesuai kebutuhan atau pesan yang ingin disampaikan.',
                                    'Elemen visualisasi karya desain ditentukan sesuai objektif pada project brief.',
                                    'Komposisi elemen visual/layout karya desain ditentukan sesuai dengan prinsip desain dan objektif.',
                                    'Alternatif/variasi karya desain dikembangkan agar melengkapi kemungkinan pencapaian konsep yang strategis.',
                                ]
                            ],
                            [
                                'nama' => 'Mendiskusikan wujud karya desain',
                                'kuk' => [
                                    'Alternatif wujud karya desain didiskusikan kesesuaiannya dengan konsep desain.',
                                    'Karya desain yang akan dijadikan purwarupa/dummy ditentukan sesuai hasil diskusi wujud karya desain.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.011.02',
                        'judul' => 'Mengevaluasi Hasil Karya Desain',
                        'elemen' => [
                            [
                                'nama' => 'Membandingkan karya-karya desain yang ada',
                                'kuk' => [
                                    'Keunggulan/keberhasilan masing-masing karya desain diidentifikasikan secara jelas dan selengkap-lengkapnya berdasarkan pengetahuan desain dan referensi terkait.',
                                    'Kelemahan/kekurangan masing-masing karya desain diidentifikasikan secara jelas dan selengkap-lengkapnya berdasarkan pengetahuan desain dan referensi terkait.',
                                    'Hasil perbandingan desain secara objektif ditimbang kembali untuk menemukan solusi yang paling sesuai dengan design brief.',
                                    'Hasil pertimbangan dikonfirmasi kembali sesuai project brief.',
                                    'Karya yang memenuhi kriteria design brief dipersiapkan untuk dipresentasikan.',
                                ]
                            ],
                        ],
                    ],
                    [
                        'kode' => 'M.74100.012.02',
                        'judul' => 'Mempresentasikan Karya Desain',
                        'elemen' => [
                            [
                                'nama' => 'Menggunakan teori desain grafis dalam memaparkan atau mengidentifikasi konsep desain',
                                'kuk' => [
                                    'Pendekatan kreatif serta segala proses dalam pencapaian hasil akhir desain disajikan secara proporsional dan pada tempatnya dan melalui progres yang berkesinambungan.',
                                    'Penggunaan media dalam karya rancangan dijelaskan sesuai dengan konsep desain.',
                                    'Penggunaan layout berikut elemennya dalam karya rancangan dijelaskan sesuai dengan konsep desain.',
                                    'Penggunaan tipografi dalam karya rancangan dijelaskan sesuai dengan konsep desain.',
                                    'Penggunaan warna dalam karya rancangan dijelaskan sesuai dengan konsep desain.',
                                ]
                            ],
                            [
                                'nama' => 'Menggunakan teori komunikasi untuk mengidentifikasi komponen karya yang dipresentasikan',
                                'kuk' => [
                                    'Subyek penyampai pesan/klien beserta kompetitornya diidentifikasi sesuai konsep desain.',
                                    'Pesan-pesan melalui karya desain diuraikan secara rasional sesuai konsep desain.',
                                    'Target atau sasaran komunikasi dijabarkan sesuai konsep desain.',
                                ]
                            ],
                            [
                                'nama' => 'Menjabarkan tahapan-tahapan desain secara menyeluruh kepada klien',
                                'kuk' => [
                                    'Permasalahan dijelaskan secara lengkap.',
                                    'Konsep solusi kreatif dijelaskan secara obyektif dan detil.',
                                    'Eksekusi desain akhir sesuai konsep awal dijelaskan secara sistematis.',
                                ]
                            ],
                            [
                                'nama' => 'Menampilkan presentasi visual',
                                'kuk' => [
                                    'Dokumentasi serta tahapan dari pengembangan dan hasil karya desain ditampilkan secara tertulis maupun lisan.',
                                    'Dummy atau purwarupa dipresentasikan dengan penjelasan secara lugas kepada klien.',
                                ]
                            ],
                        ],
                    ],
                ],
            ],

            // ─── KP 4 ───────────────────────────────────────────
            [
                'no' => 4,
                'nama' => 'Menerapkan proses produksi',
                'unit_offset' => 12,
                'unit' => [
                    [
                        'kode' => 'M.74100.013.02',
                        'judul' => 'Membuat Materi Siap Produksi',
                        'elemen' => [
                            [
                                'nama' => 'Menerjemahkan desain akhir menjadi materi siap produksi',
                                'kuk' => [
                                    'Format materi siap produksi ditentukan sesuai dengan kebutuhan produksi.',
                                    'Materi siap produksi dibuat dengan menggunakan teknik dan cara yang sesuai dengan kebutuhan produksi.',
                                ]
                            ],
                            [
                                'nama' => 'Memastikan materi siap produksi',
                                'kuk' => [
                                    'Isi materi siap produksi diperiksa kembali agar seluruh informasi di dalamnya dan kualitas tampilannya sesuai dengan kebutuhan proyek.',
                                    'Materi siap produksi ditetapkan menjadi bersifat akhir sesuai persetujuan klien.',
                                ]
                            ],
                        ],
                    ],
                ],
            ],

        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia01', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia01_preview.pdf');
});

Route::get('/ia02/preview', function () {
    $data = [
        'nama_skema' => 'AHLI DESAIN GRAFIS',
        'kode_skema' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal' => '',

        'kelompok_pekerjaan' => [
            [
                'no' => 1,
                'nama' => 'Menerapkan pengetahuan tentang desain',
                'unit' => [
                    ['kode' => 'M.74100.001.02', 'judul' => 'Mengaplikasikan Prinsip Dasar Desain'],
                    ['kode' => 'M.74100.002.02', 'judul' => 'Menerapkan Prinsip Dasar Komunikasi'],
                    ['kode' => 'M.74100.003.02', 'judul' => 'Menerapkan Pengetahuan Produksi Desain'],
                ],
                'perlengkapan' => ['A', 'B', 'C', 'Dst'],
            ],
            [
                'no' => 2,
                'nama' => 'Menentukan obyektif dari program desain',
                'unit' => [
                    ['kode' => 'M.74100.004.02', 'judul' => 'Menerapkan Project Brief'],
                    ['kode' => 'M.74100.005.02', 'judul' => 'Menerapkan Design Brief'],
                    ['kode' => 'M.74100.006.01', 'judul' => 'Mengorganisasikan Informasi Terkait Proyek Desain'],
                ],
                'perlengkapan' => ['A', 'B', 'C', 'Dst'],
            ],
            [
                'no' => 3,
                'nama' => 'Menerapkan proses desain',
                'unit' => [
                    ['kode' => 'M.74100.007.01', 'judul' => 'Menerapkan strategi Desain'],
                    ['kode' => 'M.74100.008.02', 'judul' => 'Menerapkan Konsep Design'],
                    ['kode' => 'M.74100.009.02', 'judul' => 'Mengoperasikan Perangkat Lunak Desain'],
                    ['kode' => 'M.74100.010.01', 'judul' => 'Menciptakan Karya Desain'],
                    ['kode' => 'M.74100.011.02', 'judul' => 'Mengevaluasi Hasil Karya Desain'],
                    ['kode' => 'M.74100.012.02', 'judul' => 'Mempresentasikan Karya Desain'],
                ],
                'perlengkapan' => ['A', 'B', 'C', 'Dst'],
            ],
            [
                'no' => 4,
                'nama' => 'Menerapkan proses produksi',
                'unit' => [
                    ['kode' => 'M.74100.013.02', 'judul' => 'Membuat Materi Siap Produksi'],
                ],
                'perlengkapan' => ['A', 'B', 'C', 'Dst'],
            ],
        ],

        'penyusun' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
        'validator' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia02', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia02_preview.pdf');
});

Route::get('/ia03/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal' => '',

        'kelompok' => [
            [
                'no' => 1,
                'nama' => 'Menerapkan pengetahuan tentang desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.001.02', 'judul' => 'Mengaplikasikan Prinsip Dasar Desain'],
                    ['no' => 2, 'kode' => 'M.74100.002.02', 'judul' => 'Menerapkan Prinsip Dasar Komunikasi'],
                    ['no' => 3, 'kode' => 'M.74100.003.02', 'judul' => 'Menerapkan Pengetahuan Dasar Desain'],
                ],
                'pertanyaan' => [
                    ['no' => 1, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 2, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 3, 'pertanyaan' => '', 'tanggapan' => ''],
                ],
            ],
            [
                'no' => 2,
                'nama' => 'Menentukan obyektif dari program desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.004.02', 'judul' => 'Menerapkan Project Brief'],
                    ['no' => 2, 'kode' => 'M.74100.005.02', 'judul' => 'Menerapkan Design Brief'],
                    ['no' => 3, 'kode' => 'M.74100.006.01', 'judul' => 'Mengorganisasikan Informasi Terkait Proyek Desain'],
                ],
                'pertanyaan' => [
                    ['no' => 1, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 2, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 3, 'pertanyaan' => '', 'tanggapan' => ''],
                ],
            ],
            [
                'no' => 3,
                'nama' => 'Menerapkan proses desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.007.01', 'judul' => 'Menerapkan strategi Desain'],
                    ['no' => 2, 'kode' => 'M.74100.008.02', 'judul' => 'Menerapkan Konsep Design'],
                    ['no' => 3, 'kode' => 'M.74100.009.02', 'judul' => 'Mengoperasikan Perangkat Lunak Desain'],
                    ['no' => 4, 'kode' => 'M.74100.010.01', 'judul' => 'Menciptakan Karya Desain'],
                    ['no' => 5, 'kode' => 'M.74100.011.02', 'judul' => 'Mengevaluasi Hasil Karya Desain'],
                    ['no' => 6, 'kode' => 'M.74100.012.02', 'judul' => 'Mempresentasikan Karya Desain'],
                ],
                'pertanyaan' => [
                    ['no' => 1, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 2, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 3, 'pertanyaan' => '', 'tanggapan' => ''],
                ],
            ],
            [
                'no' => 4,
                'nama' => 'Menerapkan proses produksi',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.013.02', 'judul' => 'Membuat Materi Siap Produksi'],
                ],
                'pertanyaan' => [
                    ['no' => 1, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 2, 'pertanyaan' => '', 'tanggapan' => ''],
                    ['no' => 3, 'pertanyaan' => '', 'tanggapan' => ''],
                ],
            ],
        ],

        'umpan_balik' => '',
        'asesi_nama' => '',
        'asesi_ttd_tanggal' => '',
        'asesor_nama' => '',
        'asesor_no_reg' => '',
        'asesor_ttd_tanggal' => '',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia03', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia03_preview.pdf');
});

Route::get('/ia04a/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal' => '',

        'kelompok' => [
            [
                'no' => 1,
                'nama' => 'Menerapkan pengetahuan tentang desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.001.02', 'judul' => 'Mengaplikasikan Prinsip Dasar Desain'],
                    ['no' => 2, 'kode' => 'M.74100.002.02', 'judul' => 'Menerapkan Prinsip Dasar Komunikasi'],
                    ['no' => 3, 'kode' => 'M.74100.003.02', 'judul' => 'Menerapkan Pengetahuan Dasar Desain'],
                ],
                'skenario' => '',
                'waktu_skenario' => '',
                'hasil' => '',
                'waktu_hasil' => '',
                'umpan_balik' => '',
            ],
            [
                'no' => 2,
                'nama' => 'Menentukan obyektif dari program desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.004.02', 'judul' => 'Menerapkan Project Brief'],
                    ['no' => 2, 'kode' => 'M.74100.005.02', 'judul' => 'Menerapkan Design Brief'],
                    ['no' => 3, 'kode' => 'M.74100.006.01', 'judul' => 'Mengorganisasikan Informasi Terkait Proyek Desain'],
                ],
                'skenario' => '',
                'waktu_skenario' => '',
                'hasil' => '',
                'waktu_hasil' => '',
                'umpan_balik' => '',
            ],
            [
                'no' => 3,
                'nama' => 'Menerapkan proses desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.007.01', 'judul' => 'Menerapkan strategi Desain'],
                    ['no' => 2, 'kode' => 'M.74100.008.02', 'judul' => 'Menerapkan Konsep Design'],
                    ['no' => 3, 'kode' => 'M.74100.009.02', 'judul' => 'Mengoperasikan Perangkat Lunak Desain'],
                    ['no' => 4, 'kode' => 'M.74100.010.01', 'judul' => 'Menciptakan Karya Desain'],
                    ['no' => 5, 'kode' => 'M.74100.011.02', 'judul' => 'Mengevaluasi Hasil Karya Desain'],
                    ['no' => 6, 'kode' => 'M.74100.012.02', 'judul' => 'Mempresentasikan Karya Desain'],
                ],
                'skenario' => '',
                'waktu_skenario' => '',
                'hasil' => '',
                'waktu_hasil' => '',
                'umpan_balik' => '',
            ],
            [
                'no' => 4,
                'nama' => 'Menerapkan proses produksi',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.013.02', 'judul' => 'Membuat Materi Siap Produksi'],
                ],
                'skenario' => '',
                'waktu_skenario' => '',
                'hasil' => '',
                'waktu_hasil' => '',
                'umpan_balik' => '',
            ],
        ],

        'penyusun' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
        'validator' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia04a', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia04a_preview.pdf');
});

Route::get('/ia04b/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'judul_kegiatan_terstruktur' => '',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal' => '',

        'aspek_penilaian' => [
            [
                'lingkup' => '',
                'pertanyaan' => "",
                'kesesuaian' => "",
            ],
            [
                'lingkup' => '',
                'pertanyaan' => '',
                'kesesuaian' => '',
            ],
            [
                'lingkup' => '',
                'pertanyaan' => '',
                'kesesuaian' => '',
            ],
            [
                'lingkup' => '',
                'pertanyaan' => '',
                'kesesuaian' => '',
            ],
            [
                'lingkup' => 'dst.',
                'pertanyaan' => '',
                'kesesuaian' => '',
            ],
        ],

        'penyusun' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
        'validator' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia04b', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia04b_preview.pdf');
});

Route::get('/ia05a/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal' => '',
        'waktu' => '',

        // Setiap soal punya pertanyaan + 4 pilihan (a,b,c,d)
        'soal' => [
            [
                'no' => 1,
                'pertanyaan' => '',
                'pilihan' => ['a' => '', 'b' => '', 'c' => '', 'd' => ''],
            ],
            [
                'no' => 2,
                'pertanyaan' => '',
                'pilihan' => ['a' => '', 'b' => '', 'c' => '', 'd' => ''],
            ],
            [
                'no' => 3,
                'pertanyaan' => '',
                'pilihan' => ['a' => '', 'b' => '', 'c' => '', 'd' => ''],
            ],
        ],

        'penyusun' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
        'validator' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia05a', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia05a_preview.pdf');
});

Route::get('/ia05b/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',

        'kelompok' => [
            [
                'nama' => 'Menerapkan pengetahuan tentang desain',
                'units' => [
                    ['no' => 1, 'kode' => 'M.74100.001.02', 'judul' => 'Mengaplikasikan Prinsip Dasar Desain'],
                    ['no' => 2, 'kode' => 'M.74100.002.02', 'judul' => 'Menerapkan Prinsip Dasar Komunikasi'],
                    ['no' => 3, 'kode' => 'M.74100.003.02', 'judul' => 'Menerapkan Pengetahuan Dasar Desain'],
                ],
            ],
            [
                'nama' => 'Menentukan obyektif dari program desain',
                'units' => [
                    ['no' => 4, 'kode' => 'M.74100.004.02', 'judul' => 'Menerapkan Project Brief'],
                    ['no' => 5, 'kode' => 'M.74100.005.02', 'judul' => 'Menerapkan Design Brief'],
                    ['no' => 6, 'kode' => 'M.74100.006.01', 'judul' => 'Mengorganisasikan Informasi Terkait Proyek Desain'],
                ],
            ],
            [
                'nama' => 'Menerapkan proses desain',
                'units' => [
                    ['no' => 7, 'kode' => 'M.74100.007.01', 'judul' => 'Menerapkan strategi Desain'],
                    ['no' => 8, 'kode' => 'M.74100.008.02', 'judul' => 'Menerapkan Konsep Design'],
                    ['no' => 9, 'kode' => 'M.74100.009.02', 'judul' => 'Mengoperasikan Perangkat Lunak Desain'],
                    ['no' => 10, 'kode' => 'M.74100.010.01', 'judul' => 'Menciptakan Karya Desain'],
                    ['no' => 11, 'kode' => 'M.74100.011.02', 'judul' => 'Mengevaluasi Hasil Karya Desain'],
                    ['no' => 12, 'kode' => 'M.74100.012.02', 'judul' => 'Mempresentasikan Karya Desain'],
                ],
            ],
            [
                'nama' => 'Menerapkan proses produksi',
                'units' => [
                    ['no' => 13, 'kode' => 'M.74100.013.02', 'judul' => 'Membuat Materi Siap Produksi'],
                ],
            ],
        ],

        // Kunci jawaban nomor 1-9 + dst
        'kunci_jawaban' => [
            ['no' => '1.', 'jawaban' => ''],
            ['no' => '2.', 'jawaban' => ''],
            ['no' => '3.', 'jawaban' => ''],
            ['no' => '4.', 'jawaban' => ''],
            ['no' => '5.', 'jawaban' => ''],
            ['no' => '6.', 'jawaban' => ''],
            ['no' => '7.', 'jawaban' => ''],
            ['no' => '8.', 'jawaban' => ''],
            ['no' => '9.', 'jawaban' => ''],
            ['no' => 'Dst.', 'jawaban' => ''],
        ],

        'penyusun' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
        'validator' => [
            ['nama' => '', 'nomor_met' => ''],
            ['nama' => '', 'nomor_met' => ''],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.IA.ia05b', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ia05b_preview.pdf');
});

Route::get('/ak02/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal_mulai' => '',
        'tanggal_selesai' => '',

        'unit_kompetensi' => [
            ['no' => 1, 'judul' => 'Mengaplikasikan Prinsip Dasar Desain'],
            ['no' => 2, 'judul' => 'Menerapkan Prinsip Dasar Komunikasi'],
            ['no' => 3, 'judul' => 'Menerapkan Pengetahuan Dasar Desain'],
            ['no' => 4, 'judul' => 'Menerapkan Project Brief'],
            ['no' => 5, 'judul' => 'Menerapkan Design Brief'],
            ['no' => 6, 'judul' => 'Mengorganisasikan Informasi Terkait Proyek Desain'],
            ['no' => 7, 'judul' => 'Menerapkan strategi Desain'],
            ['no' => 8, 'judul' => 'Menerapkan Konsep Design'],
            ['no' => 9, 'judul' => 'Mengoperasikan Perangkat Lunak Desain'],
            ['no' => 10, 'judul' => 'Menciptakan Karya Desain'],
            ['no' => 11, 'judul' => 'Mengevaluasi Hasil Karya Desain'],
            ['no' => 12, 'judul' => 'Mempresentasikan Karya Desain'],
            ['no' => 13, 'judul' => 'Membuat Materi Siap Produksi'],
        ],

        'rekomendasi' => '', // 'kompeten' atau 'belum_kompeten'
        'tindak_lanjut' => '',
        'komentar_observasi' => '',

        'asesi_nama' => '',
        'asesi_ttd_tanggal' => '',
        'asesor_nama' => '',
        'asesor_no_reg' => '',
        'asesor_ttd_tanggal' => '',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.AK.ak02', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ak02_preview.pdf');
});

Route::get('/ak03/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'nama_asesi' => '',
        'tanggal_mulai' => '',
        'tanggal_selesai' => '',

        'komponen' => [
            'Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi',
            'Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya',
            'Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen',
            'Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki',
            'Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen',
            'Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen',
            'Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya',
            'Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya',
            'Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen',
            'Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen',
        ],

        'catatan_lainnya' => '',
        'asesi_nama' => '',
        'asesi_ttd_tanggal' => '',
        'asesor_nama' => '',
        'asesor_no_reg' => '',
        'asesor_ttd_tanggal' => '',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.AK.ak03', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ak03_preview.pdf');
});

Route::get('/ak05/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'tanggal' => '',

        'asesi' => [
            ['no' => 1, 'nama' => '', 'keterangan' => ''],
            ['no' => 2, 'nama' => '', 'keterangan' => ''],
            ['no' => 3, 'nama' => '', 'keterangan' => ''],
            ['no' => 4, 'nama' => '', 'keterangan' => ''],
        ],

        'aspek_negatif_positif' => '',
        'pencatatan_penolakan' => '',
        'saran_perbaikan' => '',

        'catatan' => '',
        'asesor_nama' => '',
        'asesor_no_reg' => '',
        'asesor_ttd_tanggal' => '',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.AK.ak05', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ak05_preview.pdf');
});

Route::get('/ak06/preview', function () {
    $data = [
        'judul' => 'AHLI DESAIN GRAFIS',
        'nomor' => 'SUK-SKS-REV1-L007',
        'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
        'nama_asesor' => '',
        'tanggal' => '',

        // Tabel 1: Kesesuaian dengan prinsip asesmen
        // Kolom: validitas, reliabel, fleksibel, adil
        // Baris "Keputusan asesmen" dan "Umpan balik asesmen" -> fleksibel di-disable (gelap)
        'prosedur_asesmen' => [
            ['label' => 'Rencana asesmen', 'disabled' => []],
            ['label' => 'Persiapan asesmen', 'disabled' => []],
            ['label' => 'Implementasi asesmen', 'disabled' => []],
            ['label' => 'Keputusan asesmen', 'disabled' => ['fleksibel']],
            ['label' => 'Umpan balik asesmen', 'disabled' => ['fleksibel']],
        ],
        'rekomendasi_prinsip' => '',

        // Tabel 2: Pemenuhan dimensi kompetensi
        'rekomendasi_dimensi' => '',

        // Tabel 3: Lead Asesor/Asesor
        'lead_asesor_nama' => '',
        'lead_asesor_ttd' => '',
        'lead_asesor_komentar' => '',
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.AK.ak06', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('ak06_preview.pdf');
});

Route::get('/mkva/preview', function () {
    $data = [
        'nama_skema' => 'AHLI DESAIN GRAFIS',
        'nomor_skema' => 'SUK-SKS-REV1-L007',

        'tim_validasi' => ['', '', ''],
        'periode' => '', // 'sebelum' | 'pada_saat' | 'setelah'
        'hari_tanggal' => '',
        'tempat' => '',

        // Bagian 1 - Tujuan & Fokus Validasi (checkable)
        'tujuan_fokus' => [
            ['label' => 'Bagian dari Proses Penjaminan Mutu Organisasi', 'checked' => false],
            ['label' => 'Mengantisipasi Risiko', 'checked' => false],
            ['label' => 'Memenuhi Persyaratan BNSP', 'checked' => false],
            ['label' => 'Memastikan Kesesuaian Bukti', 'checked' => false],
            ['label' => 'Meningkatkan Kualitas Asesmen', 'checked' => false],
            ['label' => 'Mengevaluasi Kualitas Perangkat Asesmen', 'checked' => false],
            ['label' => '...', 'checked' => false],
        ],

        // Konteks Validasi
        'konteks_validasi' => [
            ['label' => 'Internal Organisasi', 'checked' => false],
            ['label' => 'Eksternal Organisasi', 'checked' => false],
            ['label' => 'Proses Lisensi/ Re lisensi', 'checked' => false],
            ['label' => 'Dengan Kolega Asesor', 'checked' => false],
            ['label' => 'Kolega dari Organisasi Pelatihan atau Asesmen', 'checked' => false],
            ['label' => '...', 'checked' => false],
            ['label' => '...', 'checked' => false],
        ],

        // Pendekatan Validasi
        'pendekatan_validasi' => [
            ['label' => 'Panel asesmen', 'checked' => false],
            ['label' => 'Pertemuan moderasi', 'checked' => false],
            ['label' => 'Mengkaji perangkat asesmen', 'checked' => false],
            ['label' => 'Acuan pembanding', 'checked' => false],
            ['label' => 'Pengujian lapangan dan uji coba perangkat asesmen', 'checked' => false],
            ['label' => 'Umpan balik dari klien', 'checked' => false],
            ['label' => 'Mengkaji bukti-bukti', 'checked' => false],
        ],

        // Orang yang Relevan
        'orang_relevan' => [
            ['label' => 'Asesor Kompetensi (wajib)', 'nama' => '', 'hasil' => '', 'checked' => false],
            ['label' => 'Lead Asesor [Ketua TUK]', 'nama' => '', 'hasil' => '', 'checked' => false],
            ['label' => 'Manager, Supervisor', 'nama' => '', 'hasil' => '', 'checked' => false],
            ['label' => 'Tenaga Ahli di bidangnya', 'nama' => '', 'hasil' => '', 'checked' => false],
            ['label' => 'Koordinator Pelatihan', 'nama' => '', 'hasil' => '', 'checked' => false],
            ['label' => 'Anggota Asosiasi Industry Profesi', 'nama' => '', 'hasil' => '', 'checked' => false],
        ],

        // Acuan Pembanding
        'acuan_pembanding' => [
            ['label' => 'Standar Kompetensi (SKKNI/SKKK/SKI)', 'checked' => false],
            ['label' => 'Skema Sertifikasi', 'checked' => false],
            ['label' => 'SOP/IK', 'checked' => false],
            ['label' => 'Manual Instruction / Book Manual', 'checked' => false],
            ['label' => 'Standar Kinerja', 'checked' => false],
            ['label' => '...', 'checked' => false],
        ],

        // Dokumen Terkait
        'dokumen_terkait' => [
            ['label' => 'Perangkat Asesmen', 'checked' => false],
            ['label' => 'Peraturan / Pedoman', 'checked' => false],
            ['label' => 'Bukti-bukti hasil asesmen', 'checked' => false],
            ['label' => '...', 'checked' => false],
        ],

        // Bagian 2 - Keterampilan komunikasi
        'komunikasi' => [
            ['label' => 'PRO AKTIF', 'checked' => false],
            ['label' => 'ACTIVE LISTENING', 'checked' => false],
            ['label' => 'Empasi', 'checked' => false],
        ],

        // Aspek Dalam Kegiatan Validasi
        // Aturan Bukti: V, A, T, M | Prinsip Asesmen: V, R, F, F
        'aspek_validasi' => [
            ['no' => 1, 'label' => 'Rencana Asesmen'],
            ['no' => 2, 'label' => 'Interpretasi Standar Kompetensi'],
            ['no' => 3, 'label' => 'Interpretasi Acuan Pembanding lainnya'],
            ['no' => 4, 'label' => 'Proses Asesmen'],
            ['no' => 5, 'label' => 'Penyeleksian dan Penerapan Metode Asesmen'],
            ['no' => 6, 'label' => 'Penyeleksian dan Penerapan Perangkat Asesmen'],
            ['no' => 7, 'label' => 'Bukti-bukti yang Dikumpulkan'],
            ['no' => 8, 'label' => 'Pengambilan Keputusan'],
        ],

        // Bagian 3 - Temuan & Rekomendasi
        'temuan_rekomendasi' => [
            ['no' => 1, 'temuan' => '', 'rekomendasi' => ''],
            ['no' => 2, 'temuan' => '', 'rekomendasi' => ''],
        ],

        // Rencana Implementasi
        'rencana_implementasi' => [
            ['no' => 1, 'kegiatan' => '', 'waktu' => '', 'penanggung_jawab' => ''],
            ['no' => 2, 'kegiatan' => '', 'waktu' => '', 'penanggung_jawab' => ''],
        ],
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.mkva', $data)
        ->setPaper('a4', 'portrait');

    return $pdf->stream('mkva_preview.pdf');
});
