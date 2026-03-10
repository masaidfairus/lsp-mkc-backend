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

        // Each criterion: [ 'name (KUK)', 'description (Deskripsi KUK)' ]
        $units = [
            [
                'unit_code' => 'M.741000.005.01',
                'unit_title' => 'Melakukan analisis harga pulang pokok',
                'elements' => [
                    [
                        'title' => 'Menyiapkan analisis harga pokok',
                        'criteria' => [
                            ['1.1 Pengetahuan dasar tentang tata hitung ongkos dijelaskan', 'Memahami konsep dasar perhitungan biaya produksi seperti biaya tetap, biaya variabel, dan biaya total.'],
                            ['1.2 Data kapasitas terpasang sarana produksi disiapkan', 'Menyiapkan informasi mengenai kemampuan maksimal mesin atau fasilitas produksi dalam periode tertentu.'],
                            ['1.3 Data kebutuhan tenaga kerja langsung dan tidak langsung serta gajinya per satuan waktu tertentu disiapkan', 'Mengidentifikasi jumlah tenaga kerja yang terlibat langsung maupun tidak langsung dalam produksi beserta biaya gajinya.'],
                            ['1.4 Data kebutuhan bahan baku dan bahan pembantu dan harganya per satuan waktu tertentu disiapkan', 'Menghitung kebutuhan bahan utama dan bahan pendukung produksi serta biaya pembeliannya.'],
                            ['1.5 Data kebutuhan utilitas langsung dan tidak langsung (listrik, air, uap, dll) serta harganya per satuan waktu tertentu disiapkan', 'Mencatat penggunaan utilitas produksi dan biaya yang timbul selama proses produksi.'],
                            ['1.6 Bunga bank dan nilai penyusutan dihitung', 'Menghitung beban bunga pinjaman dan penyusutan aset sebagai bagian dari biaya produksi.'],
                        ],
                    ],
                    [
                        'title' => 'Melakukan analisis harga pokok',
                        'criteria' => [
                            ['2.1 Biaya tetap dihitung setiap satuan waktu tertentu', 'Menghitung biaya yang jumlahnya tetap meskipun volume produksi berubah, seperti sewa dan gaji tetap.'],
                            ['2.2 Biaya tidak tetap dihitung untuk tiap unit produk', 'Menghitung biaya variabel yang berubah sesuai jumlah unit produk yang dihasilkan.'],
                            ['2.3 Harga jual produk tiap unit ditentukan berdasarkan biaya tetap dan biaya tidak tetap', 'Menentukan harga jual dengan mempertimbangkan total biaya produksi dan margin keuntungan.'],
                            ['2.4 Harga pokok dianalisis', 'Menganalisis hasil perhitungan harga pokok sebagai dasar pengambilan keputusan usaha.'],
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
                            ['1.1 Lokasi produksi serta harga tanah dan atau bangunannya diidentifikasi', 'Mengidentifikasi lokasi usaha serta mengetahui harga tanah dan/atau bangunan sebagai dasar perhitungan investasi awal.'],
                            ['1.2 Produsen/supplier mesin dan peralatan, serta harga setiap mesin dan peralatan diidentifikasi', 'Mengumpulkan data produsen atau supplier mesin dan peralatan beserta harga masing-masing sebagai komponen investasi.'],
                            ['1.3 Kemudahan pengadaan mesin dan peralatan dari setiap produsen/supplier diidentifikasi', 'Menilai tingkat kemudahan pengadaan mesin dan peralatan, termasuk waktu pengiriman dan ketersediaan suku cadang.'],
                        ],
                    ],
                    [
                        'title' => 'Menghitung biaya investasi untuk setiap jenis produk',
                        'criteria' => [
                            ['2.1 Lokasi produksi ditentukan berdasarkan Aturan Rencana Tata Ruang dan Wilayah yang berlaku', 'Menentukan lokasi produksi yang sesuai dengan peraturan tata ruang dan ketentuan pemerintah setempat.'],
                            ['2.2 Harga tanah dan bangunan dihitung berdasarkan harga pasar', 'Menghitung nilai investasi tanah dan bangunan berdasarkan harga pasar yang berlaku.'],
                            ['2.3 Mesin dan peralatan yang akan digunakan untuk setiap produk diidentifikasi', 'Menentukan jenis mesin dan peralatan yang dibutuhkan untuk memproduksi setiap jenis produk.'],
                            ['2.4 Harga mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'Menghitung biaya mesin dan peralatan berdasarkan daftar harga resmi atau penawaran dari distributor.'],
                            ['2.5 Biaya instalasi mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'Menghitung biaya pemasangan, instalasi, dan uji coba mesin sebagai bagian dari biaya investasi.'],
                        ],
                    ],
                    [
                        'title' => 'Melakukan analisis biaya investasi',
                        'criteria' => [
                            ['3.1 Urutan biaya investasi dibuat dari yang nilainya terendah', 'Menyusun daftar biaya investasi dari yang paling rendah untuk mengetahui alternatif investasi yang paling efisien.'],
                            ['3.2 Urutan biaya investasi dibuat berdasarkan tingkat kemudahan pengadaannya', 'Menganalisis biaya investasi berdasarkan kemudahan pengadaan untuk meminimalkan risiko keterlambatan usaha.'],
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
                            ['1.1 Instansi pemerintah yang terkait dengan pengurusan perijinan usaha industri diidentifikasi', 'Mengidentifikasi instansi pemerintah yang berwenang dalam penerbitan izin usaha industri sesuai dengan jenis dan skala usaha.'],
                            ['1.2 Prosedur dan persyaratan pengurusan perijinan usaha industri dijelaskan', 'Menjelaskan alur proses, dokumen, serta persyaratan administrasi yang harus dipenuhi dalam pengurusan izin usaha industri.'],
                        ],
                    ],
                    [
                        'title' => 'Melakukan pengurusan perijinan usaha industri',
                        'criteria' => [
                            ['2.1 Persyaratan pengurusan perijinan usaha industri disiapkan', 'Menyiapkan seluruh dokumen dan persyaratan administrasi yang dibutuhkan untuk pengajuan izin usaha industri.'],
                            ['2.2 Pengurusan perijinan usaha industri dilakukan sesuai prosedur', 'Melaksanakan proses pengurusan izin usaha industri dengan mengikuti prosedur resmi hingga izin diterbitkan.'],
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
                            ['1.1 Pengetahuan dasar tentang pengelolaan pembukuan (administrasi keuangan) dijelaskan', 'Menjelaskan konsep dasar pembukuan keuangan, termasuk tujuan, fungsi, dan prinsip pencatatan keuangan.'],
                            ['1.2 Jenis-jenis pengeluaran dan pemasukan uang diidentifikasi sesuai prosedur', 'Mengidentifikasi dan mengelompokkan jenis pemasukan dan pengeluaran keuangan sesuai dengan prosedur yang berlaku.'],
                            ['1.3 Buku kas disediakan sesuai tujuan penggunaannya', 'Menyediakan buku kas yang sesuai dengan kebutuhan pencatatan keuangan, baik secara manual maupun digital.'],
                        ],
                    ],
                    [
                        'title' => 'Melakukan pembukuan keuangan untuk setiap transaksi',
                        'criteria' => [
                            ['2.1 Setiap transaksi pengeluaran dan pemasukan uang dibukukan sesuai prosedur', 'Mencatat setiap transaksi keuangan secara benar, lengkap, dan tepat waktu sesuai prosedur pembukuan.'],
                            ['2.2 Evaluasi buku kas setiap periode (bulanan atau triwulan) dilakukan sesuai rencana', 'Melakukan pemeriksaan dan evaluasi buku kas secara berkala untuk memastikan ketepatan dan keakuratan data keuangan.'],
                            ['2.3 Penutupan buku kas dilakukan di akhir periode sesuai prosedur', 'Melakukan penutupan pembukuan pada akhir periode sebagai dasar pelaporan dan evaluasi keuangan.'],
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
                            ['1.1 Pengetahuan tentang administrasi pergudangan dijelaskan', 'Menjelaskan konsep dasar administrasi pergudangan, termasuk pencatatan, pengendalian stok, dan pengelolaan ruang simpan.'],
                            ['1.2 Daftar dan volume bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan', 'Menyusun daftar jenis serta jumlah bahan dan produk sebagai dasar perencanaan penyimpanan.'],
                            ['1.3 Data teknis dan prosedur penyimpanan masing-masing bahan baku, bahan pembantu, produk antara, dan produk akhir dikumpulkan', 'Mengumpulkan informasi teknis seperti suhu, kelembaban, masa simpan, dan cara penanganan setiap jenis bahan dan produk.'],
                            ['1.4 Metode pengambilan barang ditentukan berdasarkan FIFO (first in first out) atau LIFO (last in first out)', 'Menentukan metode pengambilan barang agar mutu terjaga dan risiko kedaluwarsa dapat diminimalkan.'],
                            ['1.5 Tempat dan peralatan penyimpanan untuk masing-masing bahan disiapkan', 'Menyiapkan ruang, rak, kontainer, dan peralatan pendukung penyimpanan sesuai karakteristik bahan.'],
                        ],
                    ],
                    [
                        'title' => 'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
                        'criteria' => [
                            ['2.1 Rencana penyimpanan bahan baku dan bahan pembantu diatur berdasarkan jenis dan spesifikasi barang', 'Mengatur tata letak penyimpanan sesuai jenis, ukuran, dan karakteristik bahan untuk memudahkan pengendalian stok.'],
                            ['2.2 Label nama bahan baku, bahan pembantu, produk antara, dan produk akhir dipasang pada masing-masing tempat penyimpanan', 'Memberi identitas yang jelas pada setiap lokasi penyimpanan untuk mencegah kesalahan pengambilan barang.'],
                            ['2.3 Administrasi penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan', 'Menyiapkan dokumen dan catatan penyimpanan seperti kartu stok atau sistem pencatatan digital.'],
                        ],
                    ],
                    [
                        'title' => 'Melaporkan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
                        'criteria' => [
                            ['3.1 Persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir dilaporkan sesuai prosedur', 'Melaporkan jumlah dan posisi persediaan secara berkala sesuai dengan prosedur organisasi.'],
                            ['3.2 Informasi persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir didokumentasikan', 'Mendokumentasikan data persediaan dan lokasi penyimpanan sebagai arsip dan bahan evaluasi.'],
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
                            ['1.1 Data permintaan produksi disiapkan sesuai prosedur', 'Menyiapkan data jumlah dan jenis permintaan produksi sebagai dasar perencanaan jadwal kerja.'],
                            ['1.2 Data kapasitas terpasang disiapkan sesuai prosedur', 'Menyiapkan data kemampuan mesin, peralatan, dan fasilitas produksi dalam periode tertentu.'],
                            ['1.3 Prosedur kerja disiapkan sesuai prosedur', 'Menyiapkan standar operasional dan alur kerja produksi yang akan menjadi acuan dalam penyusunan jadwal kerja.'],
                            ['1.4 Daftar personil bagian produksi disiapkan sesuai prosedur', 'Menyiapkan data personil produksi, termasuk jumlah dan perannya, sebagai dasar pembagian tugas dan waktu kerja.'],
                        ],
                    ],
                    [
                        'title' => 'Membuat jadwal kerja personil bagian produksi',
                        'criteria' => [
                            ['2.1 Kemampuan dan beban kerja personil bagian produksi dianalisis', 'Menganalisis kompetensi, keahlian, serta beban kerja setiap personil agar penugasan seimbang dan efektif.'],
                            ['2.2 Jadwal kerja untuk setiap personil bagian produksi dibuat berdasarkan Rencana Produksi', 'Menyusun jadwal kerja harian atau periodik sesuai rencana produksi yang telah ditetapkan.'],
                        ],
                    ],
                    [
                        'title' => 'Mensosialisasikan jadwal kerja personil bagian produksi',
                        'criteria' => [
                            ['3.1 Jadwal kerja disiapkan dalam bentuk tertulis (print-out) sesuai prosedur', 'Menyajikan jadwal kerja dalam bentuk dokumen tertulis agar mudah dipahami dan digunakan sebagai pedoman kerja.'],
                            ['3.2 Jadwal kerja dijelaskan kepada personil bagian produksi', 'Menjelaskan isi jadwal kerja kepada personil untuk memastikan pemahaman dan pelaksanaan yang tepat.'],
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
                            ['1.1 Surat/dokumen yang diterima diidentifikasi sesuai prosedur organisasi', 'Mengidentifikasi jenis, sumber, dan tujuan surat atau dokumen yang diterima sesuai ketentuan organisasi.'],
                            ['1.2 Surat/dokumen disortir sesuai dengan tujuannya', 'Mengelompokkan surat atau dokumen berdasarkan tujuan, tingkat kepentingan, dan unit kerja terkait.'],
                            ['1.3 Berita dalam surat/dokumen termasuk surat elektronik diperiksa keakuratannya', 'Memeriksa kebenaran isi informasi, tanggal, nomor, dan pihak terkait dalam surat atau dokumen.'],
                            ['1.4 Lampiran surat/dokumen termasuk surat elektronik diidentifikasi sesuai prosedur organisasi', 'Memastikan kelengkapan dan kesesuaian lampiran surat atau dokumen sesuai ketentuan yang berlaku.'],
                            ['1.5 Surat/dokumen dicatat sesuai dengan sistem yang ada pada organisasi', 'Mencatat surat atau dokumen ke dalam buku agenda, sistem arsip, atau aplikasi administrasi yang digunakan organisasi.'],
                        ],
                    ],
                    [
                        'title' => 'Mendistribusikan surat/dokumen',
                        'criteria' => [
                            ['2.1 Daftar distribusi disiapkan sesuai prosedur organisasi', 'Menyusun daftar penerima surat atau dokumen sesuai alur distribusi yang telah ditetapkan.'],
                            ['2.2 Pendistribusian surat/dokumen dilakukan dengan batas waktu', 'Menyalurkan surat atau dokumen sesuai tenggat waktu agar informasi dapat segera ditindaklanjuti.'],
                            ['2.3 Salinan surat/dokumen dibuat sesuai prosedur organisasi', 'Membuat salinan surat atau dokumen sebagai arsip atau kebutuhan unit terkait.'],
                            ['2.4 Pilihan cara pengiriman ditetapkan dengan benar dan yang terbaik sesuai kebutuhan', 'Menentukan metode pengiriman surat atau dokumen yang paling efektif, aman, dan efisien.'],
                            ['2.5 Tanda terima surat/dokumen didokumentasikan', 'Mendokumentasikan bukti penerimaan surat atau dokumen sebagai arsip dan bahan pertanggungjawaban.'],
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
                            ['1.1 Pengetahuan tentang komunikasi dijelaskan', 'Menjelaskan prinsip dan teknik komunikasi yang efektif dalam berinteraksi dengan pelanggan.'],
                            ['1.2 Spesifikasi dan manfaat produk dijelaskan', 'Menyampaikan informasi mengenai karakteristik, fungsi, dan manfaat produk kepada pelanggan.'],
                            ['1.3 Kebiasaan dan minat setiap kelompok pelanggan diidentifikasi', 'Mengidentifikasi perilaku, kebutuhan, dan minat pelanggan untuk menyesuaikan pendekatan komunikasi dan layanan.'],
                            ['1.4 Layanan informasi dan aduan pelanggan disediakan', 'Menyediakan sarana bagi pelanggan untuk memperoleh informasi dan menyampaikan keluhan atau masukan.'],
                            ['1.5 Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan disiapkan', 'Merencanakan kegiatan atau acara sebagai sarana interaksi dan penguatan hubungan dengan pelanggan.'],
                            ['1.6 Biaya penyelenggaraan acara dihitung', 'Menghitung anggaran yang dibutuhkan untuk penyelenggaraan acara pelanggan secara efektif dan efisien.'],
                        ],
                    ],
                    [
                        'title' => 'Menjalin hubungan dengan pelanggan',
                        'criteria' => [
                            ['2.1 Informasi yang jelas diberikan kepada pelanggan', 'Memberikan informasi yang akurat, lengkap, dan mudah dipahami kepada pelanggan.'],
                            ['2.2 Komunikasi langsung dengan pelanggan dilakukan sesuai prosedur', 'Melakukan komunikasi tatap muka, telepon, atau media lain sesuai standar pelayanan yang ditetapkan.'],
                            ['2.3 Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan diadakan sesuai rencana', 'Melaksanakan kegiatan atau acara pelanggan sesuai jadwal dan tujuan yang telah ditetapkan.'],
                        ],
                    ],
                    [
                        'title' => 'Menjaga hubungan dengan pelanggan',
                        'criteria' => [
                            ['3.1 Aduan dan saran pelanggan diproses sesuai prosedur', 'Menangani keluhan dan masukan pelanggan secara sistematis dan profesional.'],
                            ['3.2 Pelanggan yang potensial dihubungi sesuai prosedur', 'Melakukan tindak lanjut kepada pelanggan potensial untuk mempertahankan dan meningkatkan loyalitas.'],
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
                            ['1.1 Semua jenis produk perbankan dikenali tujuan dan fungsinya', 'Mengenali tujuan, fungsi, dan karakteristik berbagai produk perbankan seperti tabungan, giro, deposito, dan kredit.'],
                            ['1.2 Produk perbankan dipilih sesuai dengan kebutuhan perusahaan', 'Menentukan produk perbankan yang paling sesuai dengan kebutuhan operasional dan keuangan perusahaan.'],
                            ['1.3 Produk perbankan diidentifikasi sesuai dengan kebutuhan', 'Mengidentifikasi kembali produk perbankan yang digunakan agar sesuai dengan tujuan dan kebijakan perusahaan.'],
                        ],
                    ],
                    [
                        'title' => 'Melakukan transaksi perbankan',
                        'criteria' => [
                            ['2.1 Transaksi yang akan dilakukan ditulis dengan jelas pada formulir sesuai kebutuhan', 'Mengisi formulir transaksi perbankan secara lengkap dan jelas sesuai jenis transaksi yang dilakukan.'],
                            ['2.2 Pengisian formulir diperiksa ulang ketepatan penulisannya', 'Melakukan pengecekan ulang terhadap data yang ditulis untuk menghindari kesalahan transaksi.'],
                            ['2.3 Salinan dibuat apabila dibutuhkan', 'Membuat salinan formulir atau bukti transaksi sebagai arsip atau keperluan administrasi.'],
                        ],
                    ],
                    [
                        'title' => 'Mencatat dan menyimpan bukti-bukti transaksi perbankan yang sudah dilakukan',
                        'criteria' => [
                            ['3.1 Transaksi yang sudah dilakukan dicatat dalam pembukuan sesuai dengan SOP', 'Mencatat seluruh transaksi perbankan ke dalam pembukuan atau sistem keuangan sesuai standar operasional prosedur.'],
                            ['3.2 Bukti-bukti transaksi digandakan untuk disimpan dengan aman', 'Menggandakan dan menyimpan bukti transaksi secara rapi dan aman sebagai arsip keuangan.'],
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

                $criteriaRows = array_map(fn($c) => [
                    'element_id' => $elementId,
                    'name' => $c[0],
                    'description' => $c[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $elementData['criteria']);

                DB::table('performance_criteria')->insert($criteriaRows);
            }
        }
    }
}