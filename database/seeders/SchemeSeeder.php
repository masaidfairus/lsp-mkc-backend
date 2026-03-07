<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchemeSeeder extends Seeder
{
    public function run(): void
    {
        $schemeId = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.001/XI/2023',
            'name' => 'TENAGA ADMINISTRASI KEWIRAUSAHAAN',
            'type' => 'Okupasi',
            'description' => 'Skema Tenaga Administrasi Kewirausahaan adalah skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan administrasi usaha secara menyeluruh dalam mendukung operasional dan pengembangan kewirausahaan. Skema ini mencakup kompetensi dalam melakukan analisis harga pokok, menghitung biaya investasi, mengelola pembukuan keuangan dan transaksi perbankan sederhana, mengurus perizinan usaha industri, mengatur penyimpanan bahan dan produk, menyusun jadwal kerja produksi, menangani penerimaan dan pengiriman dokumen, serta menjalin hubungan dengan pelanggan. Sertifikasi ini diperuntukkan bagi tenaga kerja pada usaha mikro, kecil, dan menengah (UMKM) maupun wirausaha mandiri sebagai pengakuan atas kemampuan melaksanakan fungsi administrasi kewirausahaan sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 1250000.00,
            'recertification_fee' => 900000.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $units = [
            [
                'unit_code' => 'M.741000.005.01',
                'unit_title' => 'Melakukan analisis harga pulang pokok',
                'elements' => [
                    [
                        'title' => 'Menyiapkan analisis harga pokok',
                        'criteria' => [
                            'Pengetahuan dasar tentang tata hitung ongkos dijelaskan',
                            'Data kapasitas terpasang sarana produksi disiapkan',
                            'Data kebutuhan tenaga kerja langsung dan tidak langsung serta gajinya per satuan waktu tertentu disiapkan',
                            'Data kebutuhan bahan baku dan bahan pembantu dan harganya per satuan waktu tertentu disiapkan',
                            'Data kebutuhan utilitas langsung dan tidak langsung (listrik, air, uap, dll) serta harganya per satuan waktu tertentu disiapkan',
                            'Bunga bank dan nilai penyusutan dihitung',
                        ],
                    ],
                    [
                        'title' => 'Melakukan analisis harga pokok',
                        'criteria' => [
                            'Biaya tetap dihitung setiap satuan waktu tertentu',
                            'Biaya tidak tetap dihitung untuk tiap unit produk',
                            'Harga jual produk tiap unit ditentukan berdasarkan biaya tetap dan biaya tidak tetap',
                            'Harga pokok dianalisis',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'M.741000.003.01',
                'unit_title' => 'Menghitung biaya investasi',
                'elements' => [
                    [
                        'title' => 'Menyiapkan penghitungan biaya investasi',
                        'criteria' => [
                            'Lokasi produksi serta harga tanah dan atau bangunannya diidentifikasi',
                            'Produsen/supplier mesin dan peralatan, serta harga setiap mesin dan peralatan diidentifikasi',
                            'Kemudahan pengadaan mesin dan peralatan dari setiap produsen/supplier diidentifikasi',
                        ],
                    ],
                    [
                        'title' => 'Menghitung biaya investasi untuk setiap jenis produk',
                        'criteria' => [
                            'Lokasi produksi ditentukan berdasarkan Aturan Rencana Tata Ruang dan Wilayah yang berlaku',
                            'Harga tanah dan bangunan dihitung berdasarkan harga pasar',
                            'Mesin dan peralatan yang akan digunakan untuk setiap produk diidentifikasi',
                            'Harga mesin dan peralatan dihitung berdasarkan informasi dari distributor',
                            'Biaya instalasi mesin dan peralatan dihitung berdasarkan informasi dari distributor',
                        ],
                    ],
                    [
                        'title' => 'Melakukan analisis biaya investasi',
                        'criteria' => [
                            'Urutan biaya investasi dibuat dari yang nilainya terendah',
                            'Urutan biaya investasi dibuat berdasarkan tingkat kemudahan pengadaannya',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'M.741000.008.01',
                'unit_title' => 'Melakukan pengurusan perijinan usaha industri',
                'elements' => [
                    [
                        'title' => 'Menyiapkan pengurusan perijinan usaha industri',
                        'criteria' => [
                            'Instansi pemerintah yang terkait dengan pengurusan perijinan usaha industri diidentifikasi',
                            'Prosedur dan persyaratan pengurusan perijinan usaha industri dijelaskan',
                        ],
                    ],
                    [
                        'title' => 'Melakukan pengurusan perijinan usaha industri',
                        'criteria' => [
                            'Persyaratan pengurusan perijinan usaha industri disiapkan',
                            'Pengurusan perijinan usaha industri dilakukan sesuai prosedur',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'M.741000.027.01',
                'unit_title' => 'Melakukan pembukuan keuangan untuk setiap transaksi',
                'elements' => [
                    [
                        'title' => 'Menyiapkan pembukuan keuangan',
                        'criteria' => [
                            'Pengetahuan dasar tentang pengelolaan pembukuan (administrasi keuangan) dijelaskan',
                            'Jenis-jenis pengeluaran dan pemasukan uang diidentifikasi sesuai prosedur',
                            'Buku kas disediakan sesuai tujuan penggunaannya',
                        ],
                    ],
                    [
                        'title' => 'Melakukan pembukuan keuangan untuk setiap transaksi',
                        'criteria' => [
                            'Setiap transaksi pengeluaran dan pemasukan uang dibukukan sesuai prosedur',
                            'Evaluasi buku kas setiap periode (bulanan atau triwulan) dilakukan sesuai rencana',
                            'Penutupan buku kas dilakukan di akhir periode sesuai prosedur',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'M.741000.019.01',
                'unit_title' => 'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara dan produk akhir',
                'elements' => [
                    [
                        'title' => 'Menyiapkan pengaturan penyimpanan bahan baku dan bahan pembantu',
                        'criteria' => [
                            'Pengetahuan tentang administrasi pergudangan dijelaskan',
                            'Daftar dan volume bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan',
                            'Data teknis dan prosedur penyimpanan masing-masing bahan baku, bahan pembantu, produk antara, dan produk akhir dikumpulkan',
                            'Metode pengambilan barang ditentukan berdasarkan FIFO (first in first out) atau LIFO (last in first out)',
                            'Tempat dan peralatan penyimpanan untuk masing-masing bahan disiapkan',
                        ],
                    ],
                    [
                        'title' => 'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
                        'criteria' => [
                            'Rencana penyimpanan bahan baku dan bahan pembantu diatur berdasarkan jenis dan spesifikasi barang',
                            'Label nama bahan baku, bahan pembantu, produk antara, dan produk akhir dipasang pada masing-masing tempat penyimpanan',
                            'Administrasi penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan',
                        ],
                    ],
                    [
                        'title' => 'Melaporkan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
                        'criteria' => [
                            'Persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir dilaporkan sesuai prosedur',
                            'Informasi persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir didokumentasikan',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'M.741000.014.01',
                'unit_title' => 'Membuat jadwal kerja personil bagian produksi',
                'elements' => [
                    [
                        'title' => 'Menyiapkan pembuatan jadwal kerja',
                        'criteria' => [
                            'Data permintaan produksi disiapkan sesuai prosedur',
                            'Data kapasitas terpasang disiapkan sesuai prosedur',
                            'Prosedur kerja disiapkan sesuai prosedur',
                            'Daftar personil bagian produksi disiapkan sesuai prosedur',
                        ],
                    ],
                    [
                        'title' => 'Membuat jadwal kerja personil bagian produksi',
                        'criteria' => [
                            'Kemampuan dan beban kerja personil bagian produksi dianalisis',
                            'Jadwal kerja untuk setiap personil bagian produksi dibuat berdasarkan Rencana Produksi',
                        ],
                    ],
                    [
                        'title' => 'Mensosialisasikan jadwal kerja personil bagian produksi',
                        'criteria' => [
                            'Jadwal kerja disiapkan dalam bentuk tertulis (print-out) sesuai prosedur',
                            'Jadwal kerja dijelaskan kepada personil bagian produksi',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'N.821100.001.02',
                'unit_title' => 'Menangani Penerimaan dan Pengiriman Dokumen/Surat',
                'elements' => [
                    [
                        'title' => 'Memproses pencatatan surat/dokumen termasuk surat elektronik yang masuk dan keluar',
                        'criteria' => [
                            'Surat/dokumen yang diterima diidentifikasi sesuai prosedur organisasi',
                            'Surat/dokumen disortir sesuai dengan tujuannya',
                            'Berita dalam surat/dokumen termasuk surat elektronik diperiksa keakuratannya',
                            'Lampiran surat/dokumen termasuk surat elektronik diidentifikasi sesuai prosedur organisasi',
                            'Surat/dokumen dicatat sesuai dengan sistem yang ada pada organisasi',
                        ],
                    ],
                    [
                        'title' => 'Mendistribusikan surat/dokumen',
                        'criteria' => [
                            'Daftar distribusi disiapkan sesuai prosedur organisasi',
                            'Pendistribusian surat/dokumen dilakukan dengan batas waktu',
                            'Salinan surat/dokumen dibuat sesuai prosedur organisasi',
                            'Pilihan cara pengiriman ditetapkan dengan benar dan yang terbaik sesuai kebutuhan',
                            'Tanda terima surat/dokumen didokumentasikan',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'M.741000.026.01',
                'unit_title' => 'Menjalin hubungan dengan pelanggan',
                'elements' => [
                    [
                        'title' => 'Menyiapkan jalinan hubungan dengan pelanggan',
                        'criteria' => [
                            'Pengetahuan tentang komunikasi dijelaskan',
                            'Spesifikasi dan manfaat produk dijelaskan',
                            'Kebiasaan dan minat setiap kelompok pelanggan diidentifikasi',
                            'Layanan informasi dan aduan pelanggan disediakan',
                            'Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan disiapkan',
                            'Biaya penyelenggaraan acara dihitung',
                        ],
                    ],
                    [
                        'title' => 'Menjalin hubungan dengan pelanggan',
                        'criteria' => [
                            'Informasi yang jelas diberikan kepada pelanggan',
                            'Komunikasi langsung dengan pelanggan dilakukan sesuai prosedur',
                            'Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan diadakan sesuai rencana',
                        ],
                    ],
                    [
                        'title' => 'Menjaga hubungan dengan pelanggan',
                        'criteria' => [
                            'Aduan dan saran pelanggan diproses sesuai prosedur',
                            'Pelanggan yang potensial dihubungi sesuai prosedur',
                        ],
                    ],
                ],
            ],
            [
                'unit_code' => 'N.821100.067.01',
                'unit_title' => 'Melakukan Transaksi Perbankan Sederhana',
                'elements' => [
                    [
                        'title' => 'Mengenali produk perbankan',
                        'criteria' => [
                            'Semua jenis produk perbankan dikenali tujuan dan fungsinya',
                            'Produk perbankan dipilih sesuai dengan kebutuhan perusahaan',
                            'Produk perbankan diidentifikasi sesuai dengan kebutuhan',
                        ],
                    ],
                    [
                        'title' => 'Melakukan transaksi perbankan',
                        'criteria' => [
                            'Transaksi yang akan dilakukan ditulis dengan jelas pada formulir sesuai kebutuhan',
                            'Pengisian formulir diperiksa ulang ketepatan penulisannya',
                            'Salinan dibuat apabila dibutuhkan',
                        ],
                    ],
                    [
                        'title' => 'Mencatat dan menyimpan bukti-bukti transaksi perbankan yang sudah dilakukan',
                        'criteria' => [
                            'Transaksi yang sudah dilakukan dicatat dalam pembukuan sesuai dengan SOP',
                            'Bukti-bukti transaksi digandakan untuk disimpan dengan aman',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($units as $unitData) {
            $unitId = DB::table('competency_units')->insertGetId([
                'scheme_id' => $schemeId,
                'unit_code' => $unitData['unit_code'],
                'unit_title' => $unitData['unit_title'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($unitData['elements'] as $elementData) {
                $elementId = DB::table('competency_elements')->insertGetId([
                    'competency_unit_id' => $unitId,
                    'title' => $elementData['title'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $criteriaRows = array_map(fn($desc) => [
                    'element_id' => $elementId,
                    'description' => $desc,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $elementData['criteria']);

                DB::table('performance_criteria')->insert($criteriaRows);
            }
        }
    }
}