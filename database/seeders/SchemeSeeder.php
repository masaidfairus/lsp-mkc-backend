<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class SchemeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Schema::disableForeignKeyConstraints();

        DB::table('performance_criteria')->truncate();
        DB::table('competency_elements')->truncate();
        DB::table('competency_units')->truncate();
        DB::table('schemes')->truncate();

        Schema::enableForeignKeyConstraints();

        // ── Skema 1: TENAGA ADMINISTRASI KEWIRAUSAHAAN ──
        $scheme1 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.001/XI/2023',
            'name' => 'TENAGA ADMINISTRASI KEWIRAUSAHAAN',
            'type' => 'Okupasi',
            'description' => 'Skema Tenaga Administrasi Kewirausahaan adalah skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan administrasi usaha secara menyeluruh dalam mendukung operasional dan pengembangan kewirausahaan. Skema ini mencakup kompetensi dalam melakukan analisis harga pokok, menghitung biaya investasi, mengelola pembukuan keuangan dan transaksi perbankan sederhana, mengurus perizinan usaha industri, mengatur penyimpanan bahan dan produk, menyusun jadwal kerja produksi, menangani penerimaan dan pengiriman dokumen, serta menjalin hubungan dengan pelanggan. Sertifikasi ini diperuntukkan bagi tenaga kerja pada usaha mikro, kecil, dan menengah (UMKM) maupun wirausaha mandiri sebagai pengakuan atas kemampuan melaksanakan fungsi administrasi kewirausahaan sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 1250000,
            'recertification_fee' => 900000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit1_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.005.01',
            'unit_title' => 'Melakukan analisis harga pulang pokok',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_1,
            'title' => '1. Menyiapkan analisis harga pokok',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_1_1, 'name' => '1.1 Pengetahuan dasar tentang tata hitung ongkos dijelaskan', 'description' => 'Memahami konsep dasar perhitungan biaya produksi seperti biaya tetap, biaya variabel, dan biaya total.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_1, 'name' => '1.2 Data kapasitas terpasang sarana produksi disiapkan', 'description' => 'Menyiapkan informasi mengenai kemampuan maksimal mesin atau fasilitas produksi dalam periode tertentu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_1, 'name' => '1.3 Data kebutuhan tenaga kerja langsung dan tidak langsung serta gajinya per satuan waktu tertentu disiapkan', 'description' => 'Mengidentifikasi jumlah tenaga kerja yang terlibat langsung maupun tidak langsung dalam produksi beserta biaya gajinya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_1, 'name' => '1.4 Data kebutuhan bahan baku dan bahan pembantu dan harganya per satuan waktu tertentu disiapkan', 'description' => 'Menghitung kebutuhan bahan utama dan bahan pendukung produksi serta biaya pembeliannya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_1, 'name' => '1.5 Data kebutuhan utilitas langsung dan tidak langsung (listrik, air, uap, dll) serta harganya per satuan waktu tertentu disiapkan', 'description' => 'Mencatat penggunaan utilitas produksi dan biaya yang timbul selama proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_1, 'name' => '1.6 Bunga bank dan nilai penyusutan dihitung', 'description' => 'Menghitung beban bunga pinjaman dan penyusutan aset sebagai bagian dari biaya produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_1,
            'title' => '2. Melakukan analisis harga pokok',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_1_2, 'name' => '2.1 Biaya tetap dihitung setiap satuan waktu tertentu', 'description' => 'Menghitung biaya yang jumlahnya tetap meskipun volume produksi berubah, seperti sewa dan gaji tetap.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_2, 'name' => '2.2 Biaya tidak tetap dihitung untuk tiap unit produk', 'description' => 'Menghitung biaya variabel yang berubah sesuai jumlah unit produk yang dihasilkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_2, 'name' => '2.3 Harga jual produk tiap unit ditentukan berdasarkan biaya tetap dan biaya tidak tetap', 'description' => 'Menentukan harga jual dengan mempertimbangkan total biaya produksi dan margin keuntungan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_1_2, 'name' => '2.4 Harga pokok dianalisis', 'description' => 'Menganalisis hasil perhitungan harga pokok sebagai dasar pengambilan keputusan usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.003.01',
            'unit_title' => 'Menghitung biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_2,
            'title' => '1. Menyiapkan penghitungan biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_2_1, 'name' => '1.1 Lokasi produksi serta harga tanah dan atau bangunannya diidentifikasi', 'description' => 'Mengidentifikasi lokasi usaha serta mengetahui harga tanah dan/atau bangunan sebagai dasar perhitungan investasi awal.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_1, 'name' => '1.2 Produsen/supplier mesin dan peralatan, serta harga setiap mesin dan peralatan diidentifikasi', 'description' => 'Mengumpulkan data produsen atau supplier mesin dan peralatan beserta harga masing-masing sebagai komponen investasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_1, 'name' => '1.3 Kemudahan pengadaan mesin dan peralatan dari setiap produsen/supplier diidentifikasi', 'description' => 'Menilai tingkat kemudahan pengadaan mesin dan peralatan, termasuk waktu pengiriman dan ketersediaan suku cadang.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_2,
            'title' => '2. Menghitung biaya investasi untuk setiap jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_2_2, 'name' => '2.1 Lokasi produksi ditentukan berdasarkan Aturan Rencana Tata Ruang dan Wilayah yang berlaku', 'description' => 'Menentukan lokasi produksi yang sesuai dengan peraturan tata ruang dan ketentuan pemerintah setempat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_2, 'name' => '2.2 Harga tanah dan bangunan dihitung berdasarkan harga pasar', 'description' => 'Menghitung nilai investasi tanah dan bangunan berdasarkan harga pasar yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_2, 'name' => '2.3 Mesin dan peralatan yang akan digunakan untuk setiap produk diidentifikasi', 'description' => 'Menentukan jenis mesin dan peralatan yang dibutuhkan untuk memproduksi setiap jenis produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_2, 'name' => '2.4 Harga mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'description' => 'Menghitung biaya mesin dan peralatan berdasarkan daftar harga resmi atau penawaran dari distributor.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_2, 'name' => '2.5 Biaya instalasi mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'description' => 'Menghitung biaya pemasangan, instalasi, dan uji coba mesin sebagai bagian dari biaya investasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_2,
            'title' => '3. Melakukan analisis biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_2_3, 'name' => '3.1 Urutan biaya investasi dibuat dari yang nilainya terendah', 'description' => 'Menyusun daftar biaya investasi dari yang paling rendah untuk mengetahui alternatif investasi yang paling efisien.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_2_3, 'name' => '3.2 Urutan biaya investasi dibuat berdasarkan tingkat kemudahan pengadaannya', 'description' => 'Menganalisis biaya investasi berdasarkan kemudahan pengadaan untuk meminimalkan risiko keterlambatan usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.008.01',
            'unit_title' => 'Melakukan pengurusan perijinan usaha industri',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_3,
            'title' => '1. Menyiapkan pengurusan perijinan usaha industri',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_3_1, 'name' => '1.1 Instansi pemerintah yang terkait dengan pengurusan perijinan usaha industri diidentifikasi', 'description' => 'Mengidentifikasi instansi pemerintah yang berwenang dalam penerbitan izin usaha industri sesuai dengan jenis dan skala usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_3_1, 'name' => '1.2 Prosedur dan persyaratan pengurusan perijinan usaha industri dijelaskan', 'description' => 'Menjelaskan alur proses, dokumen, serta persyaratan administrasi yang harus dipenuhi dalam pengurusan izin usaha industri.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_3,
            'title' => '2. Melakukan pengurusan perijinan usaha industri',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_3_2, 'name' => '2.1 Persyaratan pengurusan perijinan usaha industri disiapkan', 'description' => 'Menyiapkan seluruh dokumen dan persyaratan administrasi yang dibutuhkan untuk pengajuan izin usaha industri.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_3_2, 'name' => '2.2 Pengurusan perijinan usaha industri dilakukan sesuai prosedur', 'description' => 'Melaksanakan proses pengurusan izin usaha industri dengan mengikuti prosedur resmi hingga izin diterbitkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.027.01',
            'unit_title' => 'Melakukan pembukuan keuangan untuk setiap transaksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_4,
            'title' => '1. Menyiapkan pembukuan keuangan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_4_1, 'name' => '1.1 Pengetahuan dasar tentang pengelolaan pembukuan (administrasi keuangan) dijelaskan', 'description' => 'Menjelaskan konsep dasar pembukuan keuangan, termasuk tujuan, fungsi, dan prinsip pencatatan keuangan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_4_1, 'name' => '1.2 Jenis-jenis pengeluaran dan pemasukan uang diidentifikasi sesuai prosedur', 'description' => 'Mengidentifikasi dan mengelompokkan jenis pemasukan dan pengeluaran keuangan sesuai dengan prosedur yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_4_1, 'name' => '1.3 Buku kas disediakan sesuai tujuan penggunaannya', 'description' => 'Menyediakan buku kas yang sesuai dengan kebutuhan pencatatan keuangan, baik secara manual maupun digital.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_4_1, 'name' => '2.1 Setiap transaksi pengeluaran dan pemasukan uang dibukukan sesuai prosedur', 'description' => 'Mencatat setiap transaksi keuangan secara benar, lengkap, dan tepat waktu sesuai prosedur pembukuan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_4,
            'title' => '2. Melakukan pembukuan keuangan untuk setiap transaksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_4_2, 'name' => '2.2 Evaluasi buku kas setiap periode (bulanan atau triwulan) dilakukan sesuai rencana', 'description' => 'Melakukan pemeriksaan dan evaluasi buku kas secara berkala untuk memastikan ketepatan dan keakuratan data keuangan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_4_2, 'name' => '2.3 Penutupan buku kas dilakukan di akhir periode sesuai prosedur', 'description' => 'Melakukan penutupan pembukuan pada akhir periode sebagai dasar pelaporan dan evaluasi keuangan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.019.01',
            'unit_title' => 'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_5,
            'title' => '1. Menyiapkan pengaturan penyimpanan bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_5_1, 'name' => '1.1 Pengetahuan tentang administrasi pergudangan dijelaskan', 'description' => 'Menjelaskan konsep dasar administrasi pergudangan, termasuk pencatatan, pengendalian stok, dan pengelolaan ruang simpan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_1, 'name' => '1.2 Daftar dan volume bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan', 'description' => 'Menyusun daftar jenis serta jumlah bahan dan produk sebagai dasar perencanaan penyimpanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_1, 'name' => '1.3 Data teknis dan prosedur penyimpanan masing-masing bahan baku, bahan pembantu, produk antara, dan produk akhir dikumpulkan', 'description' => 'Mengumpulkan informasi teknis seperti suhu, kelembaban, masa simpan, dan cara penanganan setiap jenis bahan dan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_1, 'name' => '1.4 Metode pengambilan barang ditentukan berdasarkan FIFO (first in first out) atau LIFO (last in first out)', 'description' => 'Menentukan metode pengambilan barang agar mutu terjaga dan risiko kedaluwarsa dapat diminimalkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_1, 'name' => '1.5 Tempat dan peralatan penyimpanan untuk masing-masing bahan disiapkan', 'description' => 'Menyiapkan ruang, rak, kontainer, dan peralatan pendukung penyimpanan sesuai karakteristik bahan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_5,
            'title' => '2. Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_5_2, 'name' => '2.1 Rencana penyimpanan bahan baku dan bahan pembantu diatur berdasarkan jenis dan spesifikasi barang', 'description' => 'Mengatur tata letak penyimpanan sesuai jenis, ukuran, dan karakteristik bahan untuk memudahkan pengendalian stok.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_2, 'name' => '2.2 Label nama bahan baku, bahan pembantu, produk antara, dan produk akhir dipasang pada masing-masing tempat penyimpanan', 'description' => 'Memberi identitas yang jelas pada setiap lokasi penyimpanan untuk mencegah kesalahan pengambilan barang.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_2, 'name' => '2.3 Administrasi penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan', 'description' => 'Menyiapkan dokumen dan catatan penyimpanan seperti kartu stok atau sistem pencatatan digital.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_5,
            'title' => '3. Melaporkan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_5_3, 'name' => '3.1 Persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir dilaporkan sesuai prosedur', 'description' => 'Melaporkan jumlah dan posisi persediaan secara berkala sesuai dengan prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_5_3, 'name' => '3.2 Informasi persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir didokumentasikan', 'description' => 'Mendokumentasikan data persediaan dan lokasi penyimpanan sebagai arsip dan bahan evaluasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.014.01',
            'unit_title' => 'Membuat jadwal kerja personil bagian produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_6,
            'title' => '1. Menyiapkan pembuatan jadwal kerja',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_6_1, 'name' => '1.1 Data permintaan produksi disiapkan sesuai prosedur', 'description' => 'Menyiapkan data jumlah dan jenis permintaan produksi sebagai dasar perencanaan jadwal kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_6_1, 'name' => '1.2 Data kapasitas terpasang disiapkan sesuai prosedur', 'description' => 'Menyiapkan data kemampuan mesin, peralatan, dan fasilitas produksi dalam periode tertentu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_6_1, 'name' => '1.3 Prosedur kerja disiapkan sesuai prosedur', 'description' => 'Menyiapkan standar operasional dan alur kerja produksi yang akan menjadi acuan dalam penyusunan jadwal kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_6_1, 'name' => '1.4 Daftar personil bagian produksi disiapkan sesuai prosedur', 'description' => 'Menyiapkan data personil produksi, termasuk jumlah dan perannya, sebagai dasar pembagian tugas dan waktu kerja.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_6,
            'title' => '2. Membuat jadwal kerja personil bagian produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_6_2, 'name' => '2.1 Kemampuan dan beban kerja personil bagian produksi dianalisis', 'description' => 'Menganalisis kompetensi, keahlian, serta beban kerja setiap personil agar penugasan seimbang dan efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_6_2, 'name' => '2.2 Jadwal kerja untuk setiap personil bagian produksi dibuat berdasarkan Rencana Produksi', 'description' => 'Menyusun jadwal kerja harian atau periodik sesuai rencana produksi yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_6_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_6,
            'title' => '3. Mensosialisasikan jadwal kerja personil bagian produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_6_3, 'name' => '3.1 Jadwal kerja disiapkan dalam bentuk tertulis (print-out) sesuai prosedur', 'description' => 'Menyajikan jadwal kerja dalam bentuk dokumen tertulis agar mudah dipahami dan digunakan sebagai pedoman kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_6_3, 'name' => '3.2 Jadwal kerja dijelaskan kepada personil bagian produksi', 'description' => 'Menjelaskan isi jadwal kerja kepada personil untuk memastikan pemahaman dan pelaksanaan yang tepat.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'N.821100.001.02',
            'unit_title' => 'Menangani Penerimaan dan Pengiriman Dokumen/Surat',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_7,
            'title' => '1. Memproses pencatatan surat/dokumen termasuk surat elektronik yang masuk dan keluar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_7_1, 'name' => '1.1 Surat/dokumen yang diterima diidentifikasi sesuai prosedur organisasi', 'description' => 'Mengidentifikasi jenis, sumber, dan tujuan surat atau dokumen yang diterima sesuai ketentuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_1, 'name' => '1.2 Surat/dokumen disortir sesuai dengan tujuannya', 'description' => 'Mengelompokkan surat atau dokumen berdasarkan tujuan, tingkat kepentingan, dan unit kerja terkait.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_1, 'name' => '1.3 Berita dalam surat/dokumen termasuk surat elektronik diperiksa keakuratannya', 'description' => 'Memeriksa kebenaran isi informasi, tanggal, nomor, dan pihak terkait dalam surat atau dokumen.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_1, 'name' => '1.4 Lampiran surat/dokumen termasuk surat elektronik diidentifikasi sesuai prosedur organisasi', 'description' => 'Memastikan kelengkapan dan kesesuaian lampiran surat atau dokumen sesuai ketentuan yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_1, 'name' => '1.5 Surat/dokumen dicatat sesuai dengan sistem yang ada pada organisasi', 'description' => 'Mencatat surat atau dokumen ke dalam buku agenda, sistem arsip, atau aplikasi administrasi yang digunakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_7,
            'title' => '2. Mendistribusikan surat/dokumen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_7_2, 'name' => '2.1 Daftar distribusi disiapkan sesuai prosedur organisasi', 'description' => 'Menyusun daftar penerima surat atau dokumen sesuai alur distribusi yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_2, 'name' => '2.2 Pendistribusian surat/dokumen dilakukan dengan batas waktu', 'description' => 'Menyalurkan surat atau dokumen sesuai tenggat waktu agar informasi dapat segera ditindaklanjuti.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_2, 'name' => '2.3 Salinan surat/dokumen dibuat sesuai prosedur organisasi', 'description' => 'Membuat salinan surat atau dokumen sebagai arsip atau kebutuhan unit terkait.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_2, 'name' => '2.4 Pilihan cara pengiriman ditetapkan dengan benar dan yang terbaik sesuai kebutuhan', 'description' => 'Menentukan metode pengiriman surat atau dokumen yang paling efektif, aman, dan efisien.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_7_2, 'name' => '2.5 Tanda terima surat/dokumen didokumentasikan', 'description' => 'Mendokumentasikan bukti penerimaan surat atau dokumen sebagai arsip dan bahan pertanggungjawaban.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_8 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'M.741000.026.01',
            'unit_title' => 'Menjalin hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_8_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_8,
            'title' => '1. Menyiapkan jalinan hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_8_1, 'name' => '1.1 Pengetahuan tentang komunikasi dijelaskan', 'description' => 'Menjelaskan prinsip dan teknik komunikasi yang efektif dalam berinteraksi dengan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_1, 'name' => '1.2 Spesifikasi dan manfaat produk dijelaskan', 'description' => 'Menyampaikan informasi mengenai karakteristik, fungsi, dan manfaat produk kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_1, 'name' => '1.3 Kebiasaan dan minat setiap kelompok pelanggan diidentifikasi', 'description' => 'Mengidentifikasi perilaku, kebutuhan, dan minat pelanggan untuk menyesuaikan pendekatan komunikasi dan layanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_1, 'name' => '1.4 Layanan informasi dan aduan pelanggan disediakan', 'description' => 'Menyediakan sarana bagi pelanggan untuk memperoleh informasi dan menyampaikan keluhan atau masukan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_1, 'name' => '1.5 Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan disiapkan', 'description' => 'Merencanakan kegiatan atau acara sebagai sarana interaksi dan penguatan hubungan dengan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_1, 'name' => '1.6 Biaya penyelenggaraan acara dihitung', 'description' => 'Menghitung anggaran yang dibutuhkan untuk penyelenggaraan acara pelanggan secara efektif dan efisien.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_8_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_8,
            'title' => '2. Menjalin hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_8_2, 'name' => '2.1 Informasi yang jelas diberikan kepada pelanggan', 'description' => 'Memberikan informasi yang akurat, lengkap, dan mudah dipahami kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_2, 'name' => '2.2 Komunikasi langsung dengan pelanggan dilakukan sesuai prosedur', 'description' => 'Melakukan komunikasi tatap muka, telepon, atau media lain sesuai standar pelayanan yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_2, 'name' => '2.3 Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan diadakan sesuai rencana', 'description' => 'Melaksanakan kegiatan atau acara pelanggan sesuai jadwal dan tujuan yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_8_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_8,
            'title' => '3. Menjaga hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_8_3, 'name' => '3.1 Aduan dan saran pelanggan diproses sesuai prosedur', 'description' => 'Menangani keluhan dan masukan pelanggan secara sistematis dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_8_3, 'name' => '3.2 Pelanggan yang potensial dihubungi sesuai prosedur', 'description' => 'Melakukan tindak lanjut kepada pelanggan potensial untuk mempertahankan dan meningkatkan loyalitas.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit1_9 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme1,
            'unit_code' => 'N.821100.067.01',
            'unit_title' => 'Melakukan Transaksi Perbankan Sederhana',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem1_9_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_9,
            'title' => '1. Mengenali produk perbankan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_9_1, 'name' => '1.1 Semua jenis produk perbankan dikenali tujuan dan fungsinya', 'description' => 'Mengenali tujuan, fungsi, dan karakteristik berbagai produk perbankan seperti tabungan, giro, deposito, dan kredit.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_9_1, 'name' => '1.2 Produk perbankan dipilih sesuai dengan kebutuhan perusahaan', 'description' => 'Menentukan produk perbankan yang paling sesuai dengan kebutuhan operasional dan keuangan perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_9_1, 'name' => '1.3 Produk perbankan diidentifikasi sesuai dengan kebutuhan', 'description' => 'Mengidentifikasi kembali produk perbankan yang digunakan agar sesuai dengan tujuan dan kebijakan perusahaan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_9_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_9,
            'title' => '2. Melakukan transaksi perbankan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_9_2, 'name' => '2.1 Transaksi yang akan dilakukan ditulis dengan jelas pada formulir sesuai kebutuhan', 'description' => 'Mengisi formulir transaksi perbankan secara lengkap dan jelas sesuai jenis transaksi yang dilakukan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_9_2, 'name' => '2.2 Pengisian formulir diperiksa ulang ketepatan penulisannya', 'description' => 'Melakukan pengecekan ulang terhadap data yang ditulis untuk menghindari kesalahan transaksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_9_2, 'name' => '2.3 Salinan dibuat apabila dibutuhkan', 'description' => 'Membuat salinan formulir atau bukti transaksi sebagai arsip atau keperluan administrasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem1_9_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit1_9,
            'title' => '3. Mencatat dan menyimpan bukti-bukti transaksi perbankan yang sudah dilakukan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem1_9_3, 'name' => '3.1 Transaksi yang sudah dilakukan dicatat dalam pembukuan sesuai dengan SOP', 'description' => 'Mencatat seluruh transaksi perbankan ke dalam pembukuan atau sistem keuangan sesuai standar operasional prosedur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem1_9_3, 'name' => '3.2 Bukti-bukti transaksi digandakan untuk disimpan dengan aman', 'description' => 'Menggandakan dan menyimpan bukti transaksi secara rapi dan aman sebagai arsip keuangan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 1: TENAGA ADMINISTRASI KEWIRAUSAHAAN');

        // ── Skema 2: TENAGA PELAYANAN PRIMA ──
        $scheme2 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.002/XI/2023',
            'name' => 'TENAGA PELAYANAN PRIMA',
            'type' => 'Okupasi',
            'description' => 'Skema Tenaga Pelayanan Prima adalah skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan memberikan layanan pelanggan secara profesional, efektif, dan berorientasi pada kepuasan pelanggan. Skema ini mencakup kompetensi dalam melakukan promosi produk, menjalin dan memelihara hubungan dengan pelanggan, melaksanakan komunikasi efektif termasuk komunikasi melalui telepon, mengelola layanan pelanggan yang berkualitas, memproses dan menindaklanjuti keluhan pelanggan, serta mengembangkan pengetahuan terhadap produk barang dan/atau jasa. Sertifikasi ini ditujukan bagi tenaga kerja yang berperan langsung dalam pelayanan pelanggan sebagai bentuk pengakuan atas kompetensi pelayanan prima sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 1750000,
            'recertification_fee' => 1200000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit2_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'M.741000.023.01',
            'unit_title' => 'Melakukan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_1,
            'title' => '1. Menyiapkan pelaksanaan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_1_1, 'name' => '1.1 Pengetahuan tentang produk dijelaskan', 'description' => 'Menjelaskan karakteristik, keunggulan, dan manfaat produk sebagai dasar kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_1_1, 'name' => '1.2 Target dan segmen pelanggan ditentukan berdasarkan jenis produk', 'description' => 'Menentukan sasaran promosi berdasarkan segmentasi pasar yang sesuai dengan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_1_1, 'name' => '1.3 Wilayah dan lokasi pemasaran ditentukan', 'description' => 'Menetapkan area dan lokasi pemasaran yang paling potensial untuk kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_1_1, 'name' => '1.4 Metode dan prosedur promosi disiapkan sesuai prosedur', 'description' => 'Menyiapkan metode promosi (online/offline) serta prosedur pelaksanaannya sesuai ketentuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_1_1, 'name' => '1.5 Personil dan peralatan promosi disiapkan sesuai prosedur', 'description' => 'Menyiapkan sumber daya manusia dan peralatan yang dibutuhkan untuk kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_1_1, 'name' => '1.6 Biaya promosi dihitung berdasarkan rencana kegiatan', 'description' => 'Menghitung anggaran promosi sesuai dengan rencana dan skala kegiatan yang akan dilaksanakan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_1,
            'title' => '2. Melakukan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_1_2, 'name' => '2.1 Promosi produk dilakukan sesuai rencana', 'description' => 'Melaksanakan kegiatan promosi sesuai jadwal, metode, dan anggaran yang telah ditentukan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_1_2, 'name' => '2.2 Masukan dari pelanggan, pengecer, dan distributor dikumpulkan', 'description' => 'Mengumpulkan umpan balik dari berbagai pihak sebagai bahan evaluasi kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_1_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_1,
            'title' => '3. Melaporkan pelaksanaan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_1_3, 'name' => '3.1 Semua masukan dianalisis berdasar prosedur yang sesuai', 'description' => 'Menganalisis seluruh masukan dan data hasil promosi sesuai prosedur evaluasi yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit2_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'M.741000.026.01',
            'unit_title' => 'Menjalin hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_2,
            'title' => '1. Menyiapkan jalinan hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_2_1, 'name' => '1.1 Pengetahuan tentang komunikasi dijelaskan', 'description' => 'Menjelaskan prinsip dan teknik komunikasi yang efektif dalam berinteraksi dengan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_1, 'name' => '1.2 Spesifikasi dan manfaat produk dijelaskan', 'description' => 'Menyampaikan informasi mengenai karakteristik, fungsi, dan manfaat produk kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_1, 'name' => '1.3 Kebiasaan dan minat setiap kelompok pelanggan diidentifikasi', 'description' => 'Mengidentifikasi perilaku, kebutuhan, dan minat pelanggan untuk menyesuaikan pendekatan komunikasi dan layanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_1, 'name' => '1.4 Layanan informasi dan aduan pelanggan disediakan', 'description' => 'Menyediakan sarana bagi pelanggan untuk memperoleh informasi dan menyampaikan keluhan atau masukan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_1, 'name' => '1.5 Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan disiapkan', 'description' => 'Merencanakan kegiatan atau acara sebagai sarana interaksi dan penguatan hubungan dengan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_1, 'name' => '1.6 Biaya penyelenggaraan acara dihitung', 'description' => 'Menghitung anggaran yang dibutuhkan untuk penyelenggaraan acara pelanggan secara efektif dan efisien.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_2,
            'title' => '2. Menjalin hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_2_2, 'name' => '2.1 Informasi yang jelas diberikan kepada pelanggan', 'description' => 'Memberikan informasi yang akurat, lengkap, dan mudah dipahami kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_2, 'name' => '2.2 Komunikasi langsung dengan pelanggan dilakukan sesuai prosedur', 'description' => 'Melakukan komunikasi tatap muka, telepon, atau media lain sesuai standar pelayanan yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_2, 'name' => '2.3 Acara-acara untuk setiap kelompok atau beberapa kelompok pelanggan diadakan sesuai rencana', 'description' => 'Melaksanakan kegiatan atau acara pelanggan sesuai jadwal dan tujuan yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_2,
            'title' => '3. Menjaga hubungan dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_2_3, 'name' => '3.1 Aduan dan saran pelanggan diproses sesuai prosedur', 'description' => 'Menangani keluhan dan masukan pelanggan secara sistematis dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_2_3, 'name' => '3.2 Pelanggan yang potensial dihubungi sesuai prosedur', 'description' => 'Melakukan tindak lanjut kepada pelanggan potensial untuk mempertahankan dan meningkatkan loyalitas.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit2_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'M.70MKT00.001.2',
            'unit_title' => 'Melaksanakan Komunikasi Efektif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_3,
            'title' => '1. Mengidentifikasi gaya komunikasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_3_1, 'name' => '1.1 Gaya komunikasi pelanggan diidentifikasi sesuai dengan karakter pelanggan.', 'description' => 'Mengamati dan mengenali tipe komunikasi pelanggan (asertif, pasif, agresif, atau pasif-agresif) agar pendekatan komunikasi dapat disesuaikan secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_3_1, 'name' => '1.2 Teknik menghadapi pelanggan dengan gaya komunikasi yang berbeda-beda diterapkan sesuai dengan kebutuhan pelanggan.', 'description' => 'Menerapkan teknik komunikasi yang sesuai untuk setiap tipe pelanggan agar interaksi berjalan efektif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_3_1, 'name' => '1.3 Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur organisasi.', 'description' => 'Menggunakan sarana komunikasi digital seperti telepon, email, dan aplikasi pesan sesuai standar dan aturan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_3,
            'title' => '2. Melaksanakan komunikasi langsung menggunakan bahasa lisan, tulisan, dan bahasa tubuh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_3_2, 'name' => '2.1 Pemilihan kata-kata saat berkomunikasi dilakukan secara cermat sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Memilih bahasa yang sopan, jelas, dan profesional agar pesan mudah dipahami dan tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_3_2, 'name' => '2.2 Bahasa tubuh, bahasa tulisan, dan ekspresi wajah dikendalikan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Mengatur sikap tubuh, ekspresi wajah, serta gaya penulisan untuk mendukung pesan komunikasi secara positif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_3_2, 'name' => '2.3 Tata penampilan profesional dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Menjaga kerapihan dan kesesuaian penampilan agar mencerminkan sikap profesional dan meningkatkan kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_3_2, 'name' => '2.4 Komunikasi antara pelanggan dan staf dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Melaksanakan komunikasi dua arah yang sopan, terbuka, dan saling menghargai sesuai norma komunikasi bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_3_2, 'name' => '2.5 Kemampuan komunikasi ditingkatkan untuk meminimalkan kesalahpahaman antar komunikan untuk mencapai komunikasi yang efektif.', 'description' => 'Mengembangkan keterampilan komunikasi melalui evaluasi dan pembelajaran agar interaksi berjalan efektif dan efisien.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit2_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'N.821100.029.02',
            'unit_title' => 'Melakukan Komunikasi melalui Telepon',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_4,
            'title' => '1. Menjawab telepon',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_4_1, 'name' => '1.1 Panggilan telepon dijawab sesuai dengan SOP.', 'description' => 'Menjawab telepon dengan salam, nada suara, dan tata cara yang telah ditetapkan organisasi agar komunikasi berlangsung profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_1, 'name' => '1.2 Bantuan ditawarkan kepada penelepon sesuai SOP.', 'description' => 'Menunjukkan sikap ramah dan proaktif dengan menawarkan bantuan sesuai kebutuhan penelepon.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_1, 'name' => '1.3 Inti pembicaraan diulangi untuk menghindari salah pengertian.', 'description' => 'Mengulang kembali pokok pembicaraan untuk memastikan pesan yang diterima dan disampaikan sudah benar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_1, 'name' => '1.4 Pertanyaan penelepon dijawab atau diteruskan kepada orang yang tepat.', 'description' => 'Memberikan jawaban yang sesuai atau mengalihkan panggilan kepada pihak yang berwenang secara tepat.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_4,
            'title' => '2. Melakukan panggilan telepon',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_4_2, 'name' => '2.1 Nomor telepon didapatkan dari sumber yang benar.', 'description' => 'Memastikan nomor tujuan diperoleh dari data resmi atau sumber yang dapat dipercaya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_2, 'name' => '2.2 Sebelum menelepon tujuan dipastikan dengan tepat.', 'description' => 'Menentukan dengan jelas maksud dan pihak yang akan dihubungi sebelum melakukan panggilan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_2, 'name' => '2.3 Nama organisasi dan alasan menelepon disampaikan sesuai SOP.', 'description' => 'Menyampaikan identitas organisasi serta tujuan menelepon secara jelas dan sopan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_2, 'name' => '2.4 Etika bertelepon sesuai SOP diterapkan saat berbicara melalui telepon.', 'description' => 'Menerapkan etika berkomunikasi, seperti intonasi, kesopanan, dan penggunaan bahasa yang tepat.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_4,
            'title' => '3. Menangani pesan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_4_3, 'name' => '3.1 Pesan yang diterima dicatat secara akurat untuk disampaikan kepada departemen/orang yang dimaksud.', 'description' => 'Mencatat pesan secara lengkap dan jelas agar dapat diteruskan tanpa kehilangan informasi penting.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_3, 'name' => '3.2 Pesan disampaikan segera kepada orang yang berhak menerimanya.', 'description' => 'Menyampaikan pesan tepat waktu kepada pihak terkait agar tidak menghambat pekerjaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_4_3, 'name' => '3.3 Panggilan telepon yang mengancam dan mencurigakan dilaporkan dengan cepat kepada yang berwenang sesuai SOP.', 'description' => 'Mengidentifikasi dan melaporkan panggilan berisiko untuk menjaga keamanan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit2_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'N.821100.046.02',
            'unit_title' => 'Mengelola Layanan Pelanggan Berkualitas',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_5,
            'title' => '1.Melaksanakan pendekatan untuk menambah kualitas pelayanan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_5_1, 'name' => '1.1 Informasi dengan penelitian informal dan formal pada kebutuhan, harapan, dan tingkat kepuasan pelanggan diidentifikasi.', 'description' => 'Mengumpulkan dan menganalisis informasi pelanggan melalui observasi, wawancara, survei, atau data lain untuk mengetahui kebutuhan dan tingkat kepuasan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_1, 'name' => '1.2 Perubahan di dalam dan di luar lingkungan dalam perencanaan kualitas jasa pelayanan dipertimbangkan.', 'description' => 'Mempertimbangkan faktor internal dan eksternal organisasi yang dapat memengaruhi kualitas pelayanan dalam proses perencanaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_1, 'name' => '1.3 Kesempatan rekan kerja untuk berpartisipasi dalam proses rencana kerja diberikan untuk meningkatkan pelayanan kepada pelanggan.', 'description' => 'Melibatkan rekan kerja dalam penyusunan rencana kerja agar tercipta rasa memiliki dan peningkatan kualitas pelayanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_1, 'name' => '1.4 Pendekatan untuk rekan kerja dan pelanggan dikomunikasikan.', 'description' => 'Menyampaikan strategi dan pendekatan pelayanan secara jelas kepada rekan kerja dan pelanggan agar dapat dipahami dan diterapkan dengan konsisten.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_5,
            'title' => '2.Memperbaiki pelayanan kepada pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_5_2, 'name' => '2.1 Standar pelayanan pelanggan di tempat kerja dipantau/monitor sesuai dengan SOP organisasi.', 'description' => 'Melakukan pemantauan secara rutin terhadap penerapan standar pelayanan untuk memastikan kesesuaian dengan SOP.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_2, 'name' => '2.2 Umpan balik dicari secara terus menerus.', 'description' => 'Mengumpulkan masukan dari pelanggan dan rekan kerja secara berkelanjutan sebagai bahan evaluasi pelayanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_2, 'name' => '2.3 Bantuan pelatihan yang terkait dengan pelayanan pelanggan diberikan kepada rekan kerja.', 'description' => 'Memberikan pelatihan atau pendampingan kepada rekan kerja untuk meningkatkan kompetensi pelayanan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_2, 'name' => '2.4 Tanggung jawab pelayanan pelanggan didorong untuk dilakukan oleh rekan kerja.', 'description' => 'Mendorong kesadaran dan tanggung jawab setiap rekan kerja dalam memberikan pelayanan terbaik kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_2, 'name' => '2.5 Perbaikan pelayanan dibuat sesuai dengan kebutuhan pelanggan untuk meningkatkan kualitas pelayanan.', 'description' => 'Melakukan perbaikan layanan berdasarkan hasil evaluasi dan kebutuhan nyata pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_5_2, 'name' => '2.6 Informasi kepada semua pihak yang berkepentingan mengenai perubahan-perubahan dalam jasa pelayanan diberikan dalam kurun waktu tertentu.', 'description' => 'Menyampaikan informasi perubahan pelayanan kepada pihak terkait secara tepat waktu agar tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit2_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'N.821100.048.01',
            'unit_title' => 'Memproses Keluhan Pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_6,
            'title' => '1. Menerapkan prinsip-prinsip kerja sama dengan kolega dan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_6_1, 'name' => '1.1 Penyebab keluhan pelanggan diidentifikasi.', 'description' => 'Mengidentifikasi sumber dan penyebab utama keluhan pelanggan melalui komunikasi, observasi, dan data pendukung.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_1, 'name' => '1.2 Bantuan pelayanan kepada pelanggan disediakan di dalam dan di luar organisasi.', 'description' => 'Memberikan bantuan pelayanan secara optimal baik melalui unit internal maupun pihak eksternal yang terkait.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_1, 'name' => '1.3 Kebutuhan dasar pelanggan diantisipasi.', 'description' => 'Mengantisipasi kebutuhan pelanggan sebelum muncul keluhan agar pelayanan berjalan lebih efektif dan proaktif.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_6,
            'title' => '2. Mengantisipasi adanya hambatan komunikasi terhadap kolega/pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_6_2, 'name' => '2.1 Pengamatan secara efektif dan keterampilan mendengarkan secara aktif digunakan untuk mendapatkan dan menerjemahkan informasi baik verbal maupun nonverbal.', 'description' => 'Menggunakan kemampuan observasi dan mendengarkan secara aktif untuk memahami pesan yang disampaikan secara lisan maupun bahasa tubuh.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_2, 'name' => '2.2 Kata-kata yang digunakan sesuai dengan SOP organisasi.', 'description' => 'Menggunakan bahasa yang sopan, jelas, dan sesuai dengan standar komunikasi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_2, 'name' => '2.3 Sikap empati dan positif diperlihatkan.', 'description' => 'Menunjukkan sikap empati, ramah, dan positif dalam berkomunikasi dengan kolega dan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_2, 'name' => '2.4 Kesempatan pelanggan menyampaikan permasalahannya diberikan.', 'description' => 'Memberikan ruang kepada pelanggan untuk menjelaskan permasalahan tanpa interupsi agar solusi yang tepat dapat diberikan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_6_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_6,
            'title' => '3. Menindaklanjuti keluhan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_6_3, 'name' => '3.1 Tindak lanjut permasalahan pelanggan diidentifikasi.', 'description' => 'Menentukan langkah-langkah lanjutan yang diperlukan untuk menyelesaikan permasalahan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_3, 'name' => '3.2 Tanggapan diberikan terhadap keluhan pelanggan.', 'description' => 'Memberikan respon yang cepat, tepat, dan sesuai prosedur terhadap keluhan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_6_3, 'name' => '3.3 Pemecahan masalah diberikan untuk kepuasan pelanggan.', 'description' => 'Menyediakan solusi yang efektif dan berorientasi pada kepuasan pelanggan sesuai kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit2_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme2,
            'unit_code' => 'M.70MKT00.033.1',
            'unit_title' => 'Mengembangkan Pengetahuan Produk (Barang/Jasa)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem2_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_7,
            'title' => '1. Mencari informasi dan pengetahuan tentang produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_7_1, 'name' => '1.1 Sumber informasi tentang produk diidentifikasi sesuai prosedur organisasi.', 'description' => 'Menentukan dan menggunakan sumber informasi resmi dan terpercaya sesuai kebijakan organisasi untuk memperoleh data produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_1, 'name' => '1.2 Fitur utama, tujuan, dan penggunaan produk diidentifikasi dari panduan spesifikasi produk.', 'description' => 'Mengkaji dokumen spesifikasi produk untuk memahami fungsi, tujuan, dan cara penggunaan produk secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_1, 'name' => '1.3 Kekuatan dan kelemahan produk diidentifikasi dari informasi yang relevan.', 'description' => 'Menganalisis keunggulan dan keterbatasan produk berdasarkan data teknis, pengalaman pengguna, dan sumber lain yang relevan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_1, 'name' => '1.4 Jaminan dan dukungan layanan diidentifikasi secara detail.', 'description' => 'Memahami ketentuan garansi, layanan purna jual, serta bentuk dukungan teknis yang diberikan kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_7,
            'title' => '2. Menerjemahkan pengetahuan produk menjadi manfaat',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_7_2, 'name' => '2.1 Daya tarik dari fitur produk diinterpretasikan sesuai kebutuhan pelanggan.', 'description' => 'Menghubungkan fitur produk dengan kebutuhan dan permasalahan pelanggan agar nilai produk mudah dipahami.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_2, 'name' => '2.2 Manfaat dan fitur produk yang kritikal dijelaskan sesuai prosedur organisasi.', 'description' => 'Menjelaskan fitur utama dan manfaat penting produk dengan bahasa yang jelas dan sesuai standar organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_2, 'name' => '2.3 Manfaat produk bagi pembeli dipresentasikan sesuai prosedur organisasi.', 'description' => 'Menyampaikan manfaat produk kepada calon pembeli melalui presentasi atau penjelasan yang terstruktur dan persuasif.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem2_7_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit2_7,
            'title' => '3. Mengevaluasi produk pesaing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem2_7_3, 'name' => '3.1 Sumber informasi produk pesaing diidentifikasi dari berbagai sumber yang relevan.', 'description' => 'Mengumpulkan data produk pesaing dari sumber internal, eksternal, dan publik yang dapat dipertanggungjawabkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_3, 'name' => '3.2 Fitur, keunggulan, kekuatan, dan kelemahan produk dibandingkan dengan produk pesaing.', 'description' => 'Melakukan analisis perbandingan produk organisasi dengan produk pesaing untuk mengetahui posisi kompetitif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem2_7_3, 'name' => '3.3 Kelebihan produk organisasi dibandingkan dengan produk pesaing dikomunikasikan kepada pembeli.', 'description' => 'Mengomunikasikan nilai tambah dan keunggulan produk organisasi secara objektif dan meyakinkan kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 2: TENAGA PELAYANAN PRIMA');

        // ── Skema 3: TENAGA PENJUALAN ONLINE ──
        $scheme3 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.003/XI/2023',
            'name' => 'TENAGA PENJUALAN ONLINE',
            'type' => 'Okupasi',
            'description' => 'Skema Tenaga Penjualan Online adalah skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan melaksanakan kegiatan penjualan dan pemasaran produk secara digital dan profesional. Skema ini mencakup kompetensi dalam melakukan promosi produk, mengidentifikasi elemen pemasaran organisasi, melaksanakan komunikasi efektif, menggunakan media sosial dan aplikasi daring, melakukan analisis pada media sosial dan media bisnis digital, mempersiapkan serta mengoptimalkan konten dan pengelolaan media sosial, menciptakan pengalaman pengguna media digital, melayani kebutuhan informasi pelanggan, menggunakan penelusur situs web, serta melaksanakan survei pasar atas suatu produk. Sertifikasi ini ditujukan bagi tenaga kerja yang berperan dalam aktivitas penjualan online sebagai bentuk pengakuan atas kompetensi sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 1750000,
            'recertification_fee' => 1200000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit3_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.741000.023.01',
            'unit_title' => 'Melakukan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_1,
            'title' => '1. Menyiapkan pelaksanaan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_1_1, 'name' => '1.1 Pengetahuan tentang produk dijelaskan', 'description' => 'Menjelaskan karakteristik, keunggulan, dan manfaat produk sebagai dasar kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_1, 'name' => '1.2 Target dan segmen pelanggan ditentukan berdasarkan jenis produk', 'description' => 'Menentukan sasaran promosi berdasarkan segmentasi pasar yang sesuai dengan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_1, 'name' => '1.3 Wilayah dan lokasi pemasaran ditentukan', 'description' => 'Menetapkan area dan lokasi pemasaran yang paling potensial untuk kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_1, 'name' => '1.4 Metode dan prosedur promosi disiapkan sesuai prosedur', 'description' => 'Menyiapkan metode promosi (online/offline) serta prosedur pelaksanaannya sesuai ketentuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_1, 'name' => '1.5 Personil dan peralatan promosi disiapkan sesuai prosedur', 'description' => 'Menyiapkan sumber daya manusia dan peralatan yang dibutuhkan untuk kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_1, 'name' => '1.6 Biaya promosi dihitung berdasarkan rencana kegiatan', 'description' => 'Menghitung anggaran promosi sesuai dengan rencana dan skala kegiatan yang akan dilaksanakan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_1,
            'title' => '2. Melakukan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_1_2, 'name' => '2.1 Promosi produk dilakukan sesuai rencana', 'description' => 'Melaksanakan kegiatan promosi sesuai jadwal, metode, dan anggaran yang telah ditentukan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_2, 'name' => '2.2 Masukan dari pelanggan, pengecer, dan distributor dikumpulkan', 'description' => 'Mengumpulkan umpan balik dari berbagai pihak sebagai bahan evaluasi kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_1_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_1,
            'title' => '3. Melaporkan pelaksanaan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_1_3, 'name' => '3.1 Semua masukan dianalisis berdasar prosedur yang sesuai', 'description' => 'Menganalisis seluruh masukan dan data hasil promosi sesuai prosedur evaluasi yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_1_3, 'name' => '3.2 Kegiatan promosi produk dilaporkan kepada pihak yang berkepentingan', 'description' => 'Menyusun dan menyampaikan laporan pelaksanaan promosi kepada manajemen atau pihak terkait.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.005.2',
            'unit_title' => 'Mengidentifikasi Elemen Pemasaran Organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_2,
            'title' => '1. Mengidentifikasi elemen strategi pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_2_1, 'name' => '1.1 Definisi segmentasi, targeting, dan positioning dijelaskan untuk memudahkan penentuan strategi pemasaran.', 'description' => 'Menjelaskan konsep segmentasi, targeting, dan positioning sebagai dasar dalam menentukan arah dan strategi pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_1, 'name' => '1.2 Segmentasi organisasi diuraikan sesuai dengan strategi organisasi.', 'description' => 'Mengelompokkan pasar berdasarkan kriteria yang relevan dan sesuai dengan tujuan serta strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_1, 'name' => '1.3 Target segmen diidentifikasi untuk memudahkan penyusunan strategi pemasaran.', 'description' => 'Menentukan segmen pasar utama yang akan dilayani sebagai fokus kegiatan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_1, 'name' => '1.4 Positioning yang ditetapkan dijabarkan sesuai dengan strategi organisasi.', 'description' => 'Menjelaskan posisi produk atau organisasi di benak konsumen sesuai dengan nilai dan keunggulan yang dimiliki.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_2,
            'title' => '2. Mengidentifikasi elemen taktik pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_2_2, 'name' => '2.1 Definisi diferensiasi, bauran pemasaran, dan penjualan dijelaskan untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Menjelaskan konsep diferensiasi, marketing mix, dan penjualan sebagai dasar penyusunan taktik pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_2, 'name' => '2.2 Kerangka diferensiasi secara infrastruktur (infrastructure), konten (content), dan konteks (context) diidentifikasi untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Mengidentifikasi bentuk diferensiasi organisasi dari sisi sarana, isi penawaran, dan konteks pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_2, 'name' => '2.3 Elemen-elemen dalam bauran pemasaran dijabarkan untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Menguraikan elemen produk, harga, distribusi, dan promosi sebagai bagian dari strategi taktis pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_2, 'name' => '2.4 Tipe-tipe pendekatan penjualan diidentifikasi untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Mengidentifikasi metode dan pendekatan penjualan yang sesuai dengan karakter produk dan target pasar.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_2,
            'title' => '3. Mengidentifikasi elemen nilai pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_2_3, 'name' => '3.1 Definisi merek (brand), layanan (service), dan proses (process) dijelaskan sesuai dengan nilai organisasi.', 'description' => 'Menjelaskan konsep merek, layanan, dan proses sebagai bagian dari nilai yang dijunjung oleh organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_3, 'name' => '3.2 Ekuitas merek (brand equity) dan elemen pembentuknya dijelaskan agar sesuai dengan nilai organisasi.', 'description' => 'Menguraikan komponen pembentuk kekuatan merek seperti kesadaran merek, persepsi kualitas, dan loyalitas pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_3, 'name' => '3.3 Dimensi kualitas pelayanan (service quality) dijabarkan agar tetap relevan dengan nilai organisasi.', 'description' => 'Menjelaskan dimensi kualitas pelayanan seperti keandalan, daya tanggap, jaminan, empati, dan bukti fisik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_2_3, 'name' => '3.4 Konsep quality, cost, delivery dan kaitannya dengan proses diuraikan agar menghasilkan proses pemasaran yang optimal.', 'description' => 'Menguraikan hubungan mutu, biaya, dan ketepatan penyampaian dalam mendukung proses pemasaran yang efektif dan efisien.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.001.2',
            'unit_title' => 'Melaksanakan Komunikasi Efektif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_3,
            'title' => '1. Mengidentifikasi gaya komunikasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_3_1, 'name' => '1.1 Gaya komunikasi pelanggan diidentifikasi sesuai dengan karakter pelanggan.', 'description' => 'Mengamati dan mengenali tipe komunikasi pelanggan (asertif, pasif, agresif, atau pasif-agresif) agar pendekatan komunikasi dapat disesuaikan secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_3_1, 'name' => '1.2 Teknik menghadapi pelanggan dengan gaya komunikasi yang berbeda-beda diterapkan sesuai dengan kebutuhan pelanggan.', 'description' => 'Menerapkan teknik komunikasi yang sesuai untuk setiap tipe pelanggan agar interaksi berjalan efektif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_3_1, 'name' => '1.3 Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur organisasi.', 'description' => 'Menggunakan sarana komunikasi digital seperti telepon, email, dan aplikasi pesan sesuai standar dan aturan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_3,
            'title' => '2. Melaksanakan komunikasi langsung menggunakan bahasa lisan, tulisan, dan bahasa tubuh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_3_2, 'name' => '2.1 Pemilihan kata-kata saat berkomunikasi dilakukan secara cermat sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Memilih bahasa yang sopan, jelas, dan profesional agar pesan mudah dipahami dan tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_3_2, 'name' => '2.2 Bahasa tubuh, bahasa tulisan, dan ekspresi wajah dikendalikan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Mengatur sikap tubuh, ekspresi wajah, serta gaya penulisan untuk mendukung pesan komunikasi secara positif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_3_2, 'name' => '2.3 Tata penampilan profesional dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Menjaga kerapihan dan kesesuaian penampilan agar mencerminkan sikap profesional dan meningkatkan kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_3_2, 'name' => '2.4 Komunikasi antara pelanggan dan staf dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Melaksanakan komunikasi dua arah yang sopan, terbuka, dan saling menghargai sesuai norma komunikasi bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.012.1',
            'unit_title' => 'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_4,
            'title' => '1. Menentukan kebutuhan organisasi dan komunikasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_4_1, 'name' => '1.1 Rancangan aksi strategis ditentukan untuk mengarahkan aktivitas.', 'description' => 'Menyusun rencana aksi strategis sebagai panduan pelaksanaan aktivitas komunikasi dan pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_1, 'name' => '1.2 Target audience dan tujuan penggunaan media sosial diidentifikasi sesuai dengan strategi pemasaran.', 'description' => 'Menentukan sasaran audiens serta tujuan penggunaan media sosial agar selaras dengan strategi pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_1, 'name' => '1.3 Media sosial dipilih yang paling cocok dengan tujuan komunikasi dan target pelanggan.', 'description' => 'Memilih platform media sosial yang paling efektif untuk menjangkau target pelanggan dan mencapai tujuan komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_1, 'name' => '1.4 Daftar tipe media sosial dan media komunikasi daring yang sesuai dibuat berdasarkan strategi pemasaran.', 'description' => 'Menyusun daftar platform dan media komunikasi daring yang akan digunakan sesuai rencana pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_4,
            'title' => '2. Mempersiapkan pilar konten',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_4_2, 'name' => '2.1 Sumber informasi eksternal dipilih yang relevan dengan rencana komunikasi.', 'description' => 'Menentukan sumber informasi eksternal yang valid dan relevan sebagai bahan pendukung konten komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_2, 'name' => '2.2 Bahasa, gaya, dan nada komunikasi digunakan sesuai dengan target konsumen.', 'description' => 'Menyesuaikan gaya bahasa, nada, dan penyampaian pesan dengan karakter dan preferensi target konsumen.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_2, 'name' => '2.3 Konten dicek untuk melihat kesesuaian dengan tujuan awal.', 'description' => 'Melakukan pengecekan konten agar tetap selaras dengan tujuan komunikasi dan strategi yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_2, 'name' => '2.4 Persetujuan didapatkan sebelum publikasi.', 'description' => 'Memastikan konten memperoleh persetujuan dari pihak berwenang sebelum dipublikasikan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_4,
            'title' => '3. Memanfaatkan fungsi platform',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_4_3, 'name' => '3.1 Format yang sudah tersedia di platform daring dimanfaatkan untuk membuat post.', 'description' => 'Menggunakan format bawaan platform (teks, gambar, video, stories, dll.) secara optimal dalam pembuatan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_3, 'name' => '3.2 Tipe file yang sesuai diunggah berdasarkan prosedur.', 'description' => 'Mengunggah jenis file yang sesuai dengan ketentuan teknis dan prosedur platform serta organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_3, 'name' => '3.3 Tautan aktif ke situs organisasi dan eksternal dibuat sesuai prosedur.', 'description' => 'Menyertakan tautan yang berfungsi dengan baik dan sesuai kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_3, 'name' => '3.4 Algoritma platform diidentifikasi dalam optimasi pemanfaatan.', 'description' => 'Memahami dan memanfaatkan algoritma platform untuk meningkatkan jangkauan dan efektivitas konten.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_4_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_4,
            'title' => '4. Mempromosikan keterikatan (engagement) kepada organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_4_4, 'name' => '4.1 Perencanaan konten dikelola secara berkala berdasarkan jadwal yang sudah ada.', 'description' => 'Mengelola dan menyesuaikan rencana konten secara rutin sesuai kalender dan jadwal yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_4, 'name' => '4.2 Post digunakan selama kegiatan pemasaran berlangsung.', 'description' => 'Memanfaatkan konten secara konsisten selama periode kampanye atau aktivitas pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_4_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_4,
            'title' => '5. Memantau keterlibatan organisasi dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_4_5, 'name' => '5.1 Aktivitas pelanggan dipantau interaksinya.', 'description' => 'Memantau respons dan interaksi pelanggan seperti komentar, pesan, dan reaksi di media sosial.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_5, 'name' => '5.2 Komunikasi dengan pelanggan dilakukan dalam waktu yang tepat secara profesional.', 'description' => 'Menanggapi pelanggan secara cepat, sopan, dan profesional sesuai etika komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_5, 'name' => '5.3 Komplain dan komentar negatif direspon dengan tepat berdasarkan prosedur yang telah ditetapkan.', 'description' => 'Menangani keluhan dan komentar negatif sesuai SOP agar reputasi organisasi tetap terjaga.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_4_5, 'name' => '5.4 Masukan pelanggan terhadap personil terkait dirujuk sesuai kebijakan dan prosedur.', 'description' => 'Menyampaikan masukan pelanggan kepada pihak terkait untuk tindak lanjut sesuai kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.013.1',
            'unit_title' => 'Melaksanakan Kegiatan Analisis di Media  Sosial dan Media Bisnis Digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_5,
            'title' => '1. Mengidentifikasi alat analisis (analytical tools) dan fungsinya',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_5_1, 'name' => '1.1 Informasi alat analisis pada media sosial didapatkan yang sesuai dengan tujuan pemasaran.', 'description' => 'Mengidentifikasi dan mengumpulkan informasi mengenai alat analisis media sosial yang relevan dengan tujuan pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_1, 'name' => '1.2 Ketersediaan alat analisis eksternal diidentifikasi berdasarkan kebutuhan komunikasi pemasaran.', 'description' => 'Menentukan ketersediaan dan kecocokan alat analisis eksternal untuk mendukung aktivitas komunikasi pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_1, 'name' => '1.3 Tipe data dan fungsi diidentifikasi untuk membantu analisis.', 'description' => 'Mengidentifikasi jenis data serta fungsi analisis yang diperlukan untuk mendukung pengambilan keputusan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_1, 'name' => '1.4 Acuan data ditentukan untuk mengukur dan meningkatkan keterikatan pelanggan (customer engagement).', 'description' => 'Menetapkan indikator dan acuan data sebagai tolok ukur tingkat keterlibatan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_5,
            'title' => '2. Mengumpulkan dan menganalisis data',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_5_2, 'name' => '2.1 Kebutuhan analisis data ditentukan, serta alat analisis dan data dipilih yang relevan dengan tujuan pemasaran.', 'description' => 'Menentukan kebutuhan analisis dan memilih alat serta data yang sesuai dengan sasaran pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_2, 'name' => '2.2 Kualitas data dipilah untuk memastikan relevansinya dengan analisis.', 'description' => 'Menyeleksi dan memverifikasi kualitas data agar hasil analisis akurat dan dapat dipercaya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_2, 'name' => '2.3 Kinerja kegiatan diidentifikasi melalui analisis statistik secara kuantitatif.', 'description' => 'Mengukur performa kegiatan pemasaran menggunakan data numerik dan metode statistik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_2, 'name' => '2.4 Data kualitatif untuk identifikasi tren dan pola dievaluasi sesuai tujuan pemasaran.', 'description' => 'Menganalisis data non-numerik untuk menemukan tren, pola perilaku, dan preferensi pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_2, 'name' => '2.5 Performa media sosial dan kehadiran daring (online presence) dipantau secara berkala.', 'description' => 'Melakukan pemantauan rutin terhadap kinerja media sosial dan eksistensi daring organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_5,
            'title' => '3. Mengevaluasi pencapaian',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_5_3, 'name' => '3.1 Data kualitatif dan kuantitatif dinilai dengan platform daring.', 'description' => 'Mengevaluasi hasil analisis menggunakan platform daring untuk memperoleh gambaran kinerja yang komprehensif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_5_3, 'name' => '3.2 Keberhasilan dari kehadiran secara daring (online presence) ditentukan dalam keterikatan pelanggan (customer engagement) dan target lainnya.', 'description' => 'Menentukan tingkat keberhasilan aktivitas daring berdasarkan indikator keterlibatan pelanggan dan pencapaian target pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.014.1',
            'unit_title' => 'Mempersiapkan Konten Digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_6,
            'title' => '1. Menentukan kebutuhan konten digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_6_1, 'name' => '1.1 Kebutuhan konten tertulis dan visual secara digital ditentukan sesuai tujuan pemasaran.', 'description' => 'Menentukan jenis konten teks dan visual yang diperlukan agar selaras dengan tujuan dan strategi pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_1, 'name' => '1.2 Format konten ditinjau untuk menjaga konsistensi.', 'description' => 'Meninjau format konten agar konsisten dengan identitas merek dan standar komunikasi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_1, 'name' => '1.3 Fungsi dan batasan platform diidentifikasi untuk pengembangan konten yang sesuai.', 'description' => 'Mengidentifikasi kemampuan dan keterbatasan tiap platform digital agar konten sesuai secara teknis dan fungsional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_1, 'name' => '1.4 Platform untuk konten internal dan eksternal ditentukan agar batas penggunaan setiap platform jelas.', 'description' => 'Menentukan platform yang digunakan untuk kebutuhan internal dan eksternal agar tidak terjadi tumpang tindih penggunaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_1, 'name' => '1.5 Informasi produk untuk pengembangan konten disesuaikan dengan tujuan pemasaran.', 'description' => 'Menyesuaikan informasi produk yang akan digunakan dalam konten dengan sasaran dan pesan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_1, 'name' => '1.6 Pengembangan konten direncanakan agar sejalan dengan citra dan aktivitas pemasaran.', 'description' => 'Menyusun rencana pengembangan konten yang mendukung citra merek dan kegiatan pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_6,
            'title' => '2. Mengembangkan konten tertulis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_6_2, 'name' => '2.1 Konten tertulis dikembangkan sesuai dengan citra dan aktivitas pemasaran.', 'description' => 'Menghasilkan konten tulisan yang mencerminkan identitas merek dan mendukung aktivitas pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_2, 'name' => '2.2 Konten tertulis dihasilkan secara akurat dan rinci.', 'description' => 'Menyusun konten tulisan dengan informasi yang benar, jelas, dan detail.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_2, 'name' => '2.3 Gaya penulisan digunakan dengan jelas sesuai target pasar.', 'description' => 'Menggunakan gaya bahasa yang sesuai dengan karakter dan preferensi target audiens.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_2, 'name' => '2.4 Teknik penulisan warta (copywriting) digunakan berdasarkan target pasar.', 'description' => 'Menerapkan teknik copywriting untuk menarik perhatian dan mendorong respons audiens.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_2, 'name' => '2.5 Kata kunci digunakan dalam penelusuran daring secara optimal.', 'description' => 'Menggunakan kata kunci yang relevan untuk meningkatkan visibilitas konten di mesin pencari.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_2, 'name' => '2.6 Susunan kalimat dan ejaan yang benar digunakan sesuai dengan isi pesan.', 'description' => 'Memastikan struktur kalimat dan ejaan sesuai kaidah bahasa agar pesan mudah dipahami.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_2, 'name' => '2.7 Masukan dari pihak lain dipertimbangkan relevansinya dan perubahan konten disusun berdasarkan tujuan pemasaran.', 'description' => 'Menyesuaikan konten tertulis berdasarkan masukan pihak terkait agar tetap sesuai tujuan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_6_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_6,
            'title' => '3. Mengembangkan konten visual',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_6_3, 'name' => '3.1 Konten visual yang sesuai digunakan dengan memperhatikan citra dan gaya pemasaran.', 'description' => 'Menggunakan visual yang konsisten dengan identitas merek dan gaya pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_3, 'name' => '3.2 Konten visual diedit guna meningkatkan kualitas dan daya tarik visual.', 'description' => 'Melakukan pengeditan visual untuk meningkatkan estetika dan kualitas tampilan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_3, 'name' => '3.3 Konten visual yang mempromosikan produk dan jasa ditentukan sesuai tujuan.', 'description' => 'Menentukan visual yang efektif untuk mendukung promosi produk atau jasa.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_3, 'name' => '3.4 Komentar dari pihak terlibat digunakan sebagai dasar perbaikan konten visual.', 'description' => 'Menyesuaikan konten visual berdasarkan masukan pihak terkait agar hasil lebih optimal.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_6_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_6,
            'title' => '4. Mengunggah konten digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_6_4, 'name' => '4.1 Persetujuan terhadap konten tertulis dan visual didapatkan dari atasan.', 'description' => 'Memastikan seluruh konten telah disetujui pihak berwenang sebelum dipublikasikan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.2 Konten diunggah ke platform digital dengan syarat file yang sesuai.', 'description' => 'Mengunggah konten sesuai ketentuan format dan ukuran file pada platform digital.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.3 Konten diatur untuk pengalaman pengguna (user experience) yang lebih baik.', 'description' => 'Mengatur tampilan dan struktur konten agar mudah diakses dan nyaman bagi pengguna.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.4 Konten disajikan dalam visual yang sesuai.', 'description' => 'Menyajikan konten dengan visual yang tepat agar pesan tersampaikan secara efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.5 Masukan dari pihak lain diperoleh untuk penilaian konten tertulis dan visual.', 'description' => 'Mengumpulkan evaluasi dari pihak terkait sebagai bahan perbaikan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.6 Konten diuji pada beberapa perangkat dan disesuaikan jika diperlukan.', 'description' => 'Menguji konten di berbagai perangkat untuk memastikan kompatibilitas dan kualitas tampilan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.7 Konten diarsipkan dan riwayat konten dikontrol secara berkala.', 'description' => 'Menyimpan konten secara teratur dan mengelola riwayat penggunaan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_6_4, 'name' => '4.8 Penyimpanan konten dan cadangannya dipastikan aman dari pencurian data.', 'description' => 'Menjamin keamanan penyimpanan konten dan data cadangannya dari risiko kehilangan atau penyalahgunaan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.015.1',
            'unit_title' => 'Mengoptimalkan Pengelolaan Media Sosial dan Rencana Aplikasi Digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_7,
            'title' => '1. Menentukan media sosial yang digunakan dan persyaratan yang dibutuhkan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_7_1, 'name' => '1.1 Aktivitas utama, basis pelanggan, nilai, dan arahan dikonfirmasi melalui strategi organisasi.', 'description' => 'Mengidentifikasi aktivitas inti organisasi, karakter pelanggan, nilai, serta arah organisasi sebagai dasar pemilihan media sosial.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_1, 'name' => '1.2 Tujuan penggunaan media sosial dan platform daring ditentukan mengikuti strategi organisasi.', 'description' => 'Menetapkan tujuan penggunaan media sosial agar selaras dengan strategi dan sasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_1, 'name' => '1.3 Fungsi platform dinilai untuk melayani segmen pelanggan tertentu.', 'description' => 'Menilai kemampuan masing-masing platform dalam menjangkkau dan melayani segmen pelanggan yang dituju.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_1, 'name' => '1.4 Media sosial dan platform daring dipilih yang paling cocok dengan tujuan dan target pelanggan.', 'description' => 'Menentukan platform yang paling efektif berdasarkan tujuan komunikasi dan karakter target pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_7,
            'title' => '2. Menentukan kriteria dan kinerja platform media sosial',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_7_2, 'name' => '2.1 Tipe dan jumlah media sosial/media daring ditentukan berdasarkan strategi organisasi.', 'description' => 'Menentukan jenis dan jumlah platform yang digunakan sesuai dengan kebutuhan dan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_2, 'name' => '2.2 Media sosial/media daring lain diidentifikasi peluangnya dalam mendukung strategi organisasi.', 'description' => 'Mengidentifikasi peluang penggunaan platform lain yang berpotensi mendukung pencapaian tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_2, 'name' => '2.3 Indikator kinerja utama dan kriteria evaluasi dibentuk untuk mengukur efektivitas media sosial/aktivitas daring lainnya.', 'description' => 'Menetapkan KPI dan kriteria evaluasi untuk mengukur keberhasilan aktivitas media sosial.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_2, 'name' => '2.4 Metode analisis performa diidentifikasi, serta analisis, evaluasi, dan pelaporan dijadwalkan secara berkala.', 'description' => 'Menentukan metode analisis dan menyusun jadwal evaluasi serta pelaporan kinerja secara rutin.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_2, 'name' => '2.5 Implementasi anggaran, peran, dan kewajiban ditentukan untuk anggota yang terlibat.', 'description' => 'Menetapkan pembagian anggaran, peran, dan tanggung jawab tim yang terlibat dalam pengelolaan media sosial.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_7_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_7,
            'title' => '3. Mengembangkan pedoman konten (pilar konten)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_7_3, 'name' => '3.1 Implementasi anggaran, peran, dan kewajiban ditentukan untuk anggota yang terlibat.', 'description' => 'Menetapkan pengelolaan sumber daya dan tanggung jawab tim dalam pengembangan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_3, 'name' => '3.2 Aturan/standar untuk merespon dikembangkan dalam interaksi dengan pelanggan.', 'description' => 'Menyusun standar respon komunikasi agar interaksi dengan pelanggan konsisten dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_3, 'name' => '3.3 Aturan/standar penggunaan tulisan dan gambar dibentuk berdasarkan peraturan organisasi.', 'description' => 'Menetapkan pedoman penggunaan bahasa dan visual sesuai kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_3, 'name' => '3.4 Ukuran kesopanan ditentukan dalam konten dan aturan batas informasi yang dapat dipublikasikan.', 'description' => 'Menentukan etika komunikasi serta batasan informasi yang boleh dipublikasikan kepada publik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_3, 'name' => '3.5 Proses persetujuan konten dan tingkat kontrol internal ditentukan sesuai prosedur organisasi.', 'description' => 'Menetapkan alur persetujuan dan mekanisme kontrol internal sebelum konten dipublikasikan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_3, 'name' => '3.6 Pedoman konten dikomunikasikan kepada pihak yang terlibat.', 'description' => 'Mensosialisasikan pedoman konten kepada seluruh pihak terkait agar dipahami dan diterapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_7_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_7,
            'title' => '4. Mengembangkan dan mendokumentasikan aturan dan prosedur',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_7_4, 'name' => '4.1 Kebijakan terkait pelanggan di media sosial/situs daring dikembangkan mengikuti proses bisnis organisasi.', 'description' => 'Menyusun kebijakan pengelolaan pelanggan di media sosial sesuai proses bisnis organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_4, 'name' => '4.2 Kebijakan dan prosedur aktivitas dikembangkan sebagai pedoman bagi para pihak terlibat.', 'description' => 'Menyediakan panduan tertulis sebagai acuan pelaksanaan aktivitas media sosial.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_4, 'name' => '4.3 Kebijakan, prosedur, dan maksud organisasi dikomunikasikan kepada internal dan pelanggan.', 'description' => 'Menginformasikan kebijakan dan tujuan organisasi kepada pihak internal dan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_7_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_7,
            'title' => '5. Mengimplementasikan strategi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_7_5, 'name' => '5.1 Rencana aksi dikembangkan berdasarkan strategi organisasi.', 'description' => 'Menyusun rencana aksi operasional yang mengacu pada strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_5, 'name' => '5.2 Dokumentasi untuk media sosial/aktivitas daring dipersiapkan sesuai strategi organisasi.', 'description' => 'Menyiapkan dokumen pendukung pelaksanaan aktivitas media sosial sesuai strategi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_5, 'name' => '5.3 Prioritas, tanggung jawab, target, dan jadwal (timeline) diidentifikasi dan dikomunikasikan kepada tim.', 'description' => 'Menetapkan prioritas kerja, target, serta jadwal pelaksanaan dan menyampaikannya kepada tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_7_5, 'name' => '5.4 Aktivitas daring/media sosial dipantau secara berkala.', 'description' => 'Melakukan pemantauan rutin untuk memastikan aktivitas berjalan sesuai rencana dan target.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_8 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.70MKT00.016.1',
            'unit_title' => 'Menciptakan Pengalaman Bagi Pengguna Media Digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_8_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_8,
            'title' => '1. Menentukan tujuan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_8_1, 'name' => '1.1 Tujuan ditentukan untuk menjalin keterikatan di media digital.', 'description' => 'Menetapkan tujuan yang jelas agar aktivitas digital fokus pada peningkatan interaksi dan keterlibatan pengguna.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_1, 'name' => '1.2 Karakteristik produk, brand, atau organisasi ditentukan berdasarkan strategi organisasi.', 'description' => 'Mengidentifikasi ciri utama produk/brand agar pesan digital selaras dengan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_1, 'name' => '1.3 Kerangka undang-undang, peraturan, dan kebijakan yang relevan diidentifikasi sesuai strategi organisasi.', 'description' => 'Memastikan seluruh aktivitas digital mematuhi regulasi dan kebijakan yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_8_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_8,
            'title' => '2. Mengklasifikasi pengguna dan tipe percakapan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_8_2, 'name' => '2.1 Pengguna diklasifikasi menurut pola keterikatan, keadaan sosial-ekonomi, dan karakteristik media digital.', 'description' => 'Mengelompokkan pengguna untuk memahami perilaku dan preferensi mereka di media digital.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_2, 'name' => '2.2 Tipe percakapan diidentifikasi melalui analisis data penggunaan media digital.', 'description' => 'Menganalisis data untuk mengetahui jenis interaksi yang paling sering dan relevan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_2, 'name' => '2.3 Tipe percakapan berdasarkan perangkat, platform, dan maksud penggunaan didapatkan secara sistematis.', 'description' => 'Menentukan pola percakapan berdasarkan media dan tujuan penggunaan secara terstruktur.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_8_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_8,
            'title' => '3. Menggambarkan pengalaman pelanggan dan perjalanan pelanggan di media digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_8_3, 'name' => '3.1 Komunitas digital dan pola keterikatan pengguna diidentifikasi kesesuaiannya dengan tujuan.', 'description' => 'Menilai apakah komunitas dan interaksi digital sudah mendukung tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_3, 'name' => '3.2 Titik kontak digital diklasifikasikan dengan produk, merek, atau organisasi.', 'description' => 'Mengidentifikasi semua saluran digital yang menjadi titik interaksi pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_3, 'name' => '3.3 Model perjalanan pengguna yang tidak umum dibuat untuk peluang penambahan pengguna.', 'description' => 'Mengembangkan user journey alternatif untuk menjangkau dan menarik pengguna baru.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_3, 'name' => '3.4 Lokasi, gaya, dan pemicu intervensi dipilih untuk pengalaman terbaik.', 'description' => 'Menentukan waktu dan cara interaksi yang paling efektif dalam perjalanan pengguna.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_8_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_8,
            'title' => '4. Menciptakan kesempatan untuk menjalin keterikatan dengan pelanggan dan event',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_8_4, 'name' => '4.1 Kisah para pengguna dikembangkan untuk meningkatkan keterikatan.', 'description' => 'Menggunakan storytelling berbasis pengalaman pengguna untuk meningkatkan engagement.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_4, 'name' => '4.2 Acara daring dirancang untuk memicu keterikatan dan meningkatkan pengalaman.', 'description' => 'Merancang event digital yang interaktif dan bernilai bagi pengguna.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_4, 'name' => '4.3 Desain antar muka ditentukan berdasarkan data pengalaman pengguna.', 'description' => 'Menyesuaikan tampilan dan navigasi agar mudah digunakan dan menarik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_4, 'name' => '4.4 Indikator kinerja untuk pengalaman dan keterikatan ditetapkan.', 'description' => 'Menentukan KPI untuk mengukur keberhasilan UX dan engagement.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_8_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_8,
            'title' => '5. Berkomunikasi, mengawasi, dan merespon pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_8_5, 'name' => '5.1 Interaksi real time difasilitasi sesuai desain media digital.', 'description' => 'Menyediakan komunikasi langsung sesuai konsep dan platform digital yang digunakan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_5, 'name' => '5.2 Keterikatan pengguna dipantau dengan teknik dan media yang sesuai.', 'description' => 'Melakukan monitoring engagement secara berkala menggunakan alat yang relevan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_5, 'name' => '5.3 Pengguna direspon dengan parameter indikator kerja.', 'description' => 'Memberikan respon sesuai standar layanan dan target kinerja yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_8_6 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_8,
            'title' => '6. Mengulas dan mengevaluasi berdasarkan pengalaman pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_8_6, 'name' => '6.1 Data digital pengguna disusun berdasarkan tahapan pengalaman pelanggan.', 'description' => 'Mengelola data pengguna sesuai alur customer journey.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_6, 'name' => '6.2 Data dikumpulkan untuk mengidentifikasi pengecualian dari ekspektasi pengguna.', 'description' => 'Menemukan masalah atau anomali yang memengaruhi pengalaman pengguna.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_8_6, 'name' => '6.3 Masalah dan rekomendasi dikumpulkan untuk perbaikan selanjutnya.', 'description' => 'Menyusun evaluasi dan rekomendasi guna meningkatkan pengalaman pengguna di masa depan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_9 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.702093.011.01',
            'unit_title' => 'Melayani Kebutuhan Informasi     Bagi Pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_9_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_9,
            'title' => '1. Mengklasifikasi kebutuhan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_9_1, 'name' => '1.1 Kebutuhan pelanggan diidentifikasi secara detail.', 'description' => 'Deskripsi / Penjelasan KUK', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_9_1, 'name' => '1.2 Kebutuhan pelanggan diklasifikasikan sesuai dengan kebutuhan.', 'description' => 'Menggali dan memahami kebutuhan pelanggan secara menyeluruh melalui komunikasi yang efektif dan pengamatan yang tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_9_1, 'name' => '1.3 Detail kebutuhan pelanggan didokumentasikan dalam format yang telah ditentukan.', 'description' => 'Mengelompokkan kebutuhan pelanggan berdasarkan jenis, prioritas, atau karakteristik agar mudah ditangani.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_9_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_9,
            'title' => '2. Menindaklanjuti kebutuhan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_9_2, 'name' => '2.1 Kebutuhan pelanggan dikumpulkan dengan tepat dan profesional.', 'description' => 'Mencatat hasil identifikasi dan klasifikasi kebutuhan pelanggan secara sistematis sesuai standar atau prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_9_2, 'name' => '2.2 Saran dan informasi terkait kebutuhan pelanggan dikomunikasikan kepada pelanggan.', 'description' => 'Menghimpun informasi kebutuhan pelanggan secara akurat dengan sikap profesional dan etika layanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_9_2, 'name' => '2.3 Umpan balik terhadap saran dan informasi dievaluasi berdasarkan ketentuan yang berlaku.', 'description' => 'Menyampaikan solusi, saran, atau informasi yang relevan sesuai kebutuhan pelanggan secara jelas dan mudah dipahami.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_10 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'J.63OPR00.007.2',
            'unit_title' => 'Menggunakan Penelusur  Situs  Web  (Web Browser)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_10_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_10,
            'title' => '1. Mempersiapkan perangkat lunak penelusur situs web',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_10_1, 'name' => '1.1 Ikon dan/atau shortcut link penelusur situs web pada layar perangkat komputer dikenali sesuai dengan user manual.', 'description' => 'Mengidentifikasi ikon atau shortcut browser pada perangkat komputer sesuai petunjuk penggunaan agar dapat digunakan dengan benar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_1, 'name' => '1.2 Penelusur situs web dijalankan dengan mengklik ikon pada layar dan tampilan muncul pada layar.', 'description' => 'Mengoperasikan browser dengan cara yang benar hingga tampilan utama aplikasi berhasil ditampilkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_1, 'name' => '1.3 Menu dan perintah shortcut penelusur situs web dikenali sesuai dengan user manual.', 'description' => 'Mengenali fungsi menu dan shortcut pada browser untuk mendukung kegiatan penelusuran internet.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_10_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_10,
            'title' => '2. Mengakses internet melalui URL (Uniform Resource Locator)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_10_2, 'name' => '2.1 Pranala (hyperlink) pada situs internet dijelajahi sesuai URL yang tersedia.', 'description' => 'Mengakses dan menjelajahi tautan pada situs web dengan menggunakan alamat URL yang benar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_2, 'name' => '2.2 Halaman situs web hasil penelusuran (browsing) disimpan pada media penyimpanan sesuai lokasi penyimpanan yang tersedia.', 'description' => 'Menyimpan halaman web yang dibutuhkan ke media penyimpanan dengan lokasi yang tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_2, 'name' => '2.3 Halaman situs web hasil penelusuran (browsing) dicetak ke printer.', 'description' => 'Mencetak halaman web sesuai kebutuhan menggunakan perangkat printer yang tersedia.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_2, 'name' => '2.4 Berkas pada halaman situs web diunduh sesuai dengan petunjuk situs.', 'description' => 'Mengunduh file dari situs web dengan mengikuti prosedur dan petunjuk yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_10_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_10,
            'title' => '3. Memanfaatkan mesin pencari informasi di internet (search engine)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_10_3, 'name' => '3.1 Penelusuran dengan mempergunakan kata kunci dilakukan sesuai dengan topik yang tersedia.', 'description' => 'Mencari informasi menggunakan kata kunci yang relevan untuk memperoleh hasil yang sesuai kebutuhan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_3, 'name' => '3.2 Penelusuran dengan mempergunakan search operator dilakukan sesuai dengan topik yang tersedia.', 'description' => 'Menggunakan operator pencarian (misalnya tanda kutip, AND, OR) untuk mempersempit dan memperjelas hasil pencarian.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_10_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_10,
            'title' => '4. Menggunakan markah buku (bookmark)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_10_4, 'name' => '4.1 URL situs web disimpan pada bookmark sesuai lokasi bookmark yang telah ada.', 'description' => 'Menyimpan alamat situs web penting ke dalam bookmark agar mudah diakses kembali.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_4, 'name' => '4.2 Bookmark dapat dikelola, seperti dengan menambahkan folder dan mengatur isi dari tiap folder.', 'description' => 'Mengatur bookmark secara sistematis dengan membuat folder dan mengelompokkan situs sesuai kebutuhan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_10_4, 'name' => '4.3 Situs web diakses dengan mempergunakan alamat yang terdapat pada bookmark.', 'description' => 'Mengakses kembali situs web dengan cepat melalui daftar bookmark yang telah disimpan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit3_11 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme3,
            'unit_code' => 'M.741000.001.01',
            'unit_title' => 'Melaksanakan survey pasar atas suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem3_11_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_11,
            'title' => '1. Menyiapkan pelaksanaan survey pasar atas suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_11_1, 'name' => '1.1 Pengetahuan dasar tentang survey dijelaskan.', 'description' => 'Menjelaskan konsep dasar survei pasar, termasuk pengertian, tujuan, dan manfaat survei sebagai dasar pengambilan keputusan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_1, 'name' => '1.2 Masalah yang akan disurvey diidentifikasi.', 'description' => 'Mengidentifikasi permasalahan pasar yang menjadi fokus survei agar data yang dikumpulkan relevan dengan kebutuhan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_1, 'name' => '1.3 Tujuan survey pasar ditentukan untuk dasar pelaksanaan survey.', 'description' => 'Menentukan tujuan survei pasar secara jelas sebagai acuan dalam penyusunan rencana dan metode survei.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_11_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_11,
            'title' => '2. Menyusun rencana survey pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_11_2, 'name' => '2.1 Lokasi dan sumber data ditentukan berdasarkan lokasi pemasaran produk yang akan dibuat.', 'description' => 'Menentukan lokasi survei dan sumber data yang sesuai dengan wilayah pemasaran produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_2, 'name' => '2.2 Metoda survey ditentukan berdasarkan tujuan survey.', 'description' => 'Memilih metode survei (misalnya wawancara, kuesioner, observasi) yang paling sesuai untuk mencapai tujuan survei.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_2, 'name' => '2.3 Biaya pelaksanaan survey pasar dihitung berdasarkan kegiatan yang akan dilaksanakan dan biaya per kegiatan.', 'description' => 'Menghitung anggaran survei secara rinci sesuai aktivitas yang dilakukan agar pelaksanaan survei efisien dan terkontrol.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_2, 'name' => '2.4 Alat untuk pengumpulan data dalam pelaksanaan survey pasar disiapkan.', 'description' => 'Menyiapkan instrumen survei seperti kuesioner, formulir, atau perangkat digital untuk pengumpulan data.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_11_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_11,
            'title' => '3. Melaksanakan survey pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_11_3, 'name' => '3.1 Spesifikasi dan harga setiap jenis produk dikumpulkan.', 'description' => 'Mengumpulkan data terkait spesifikasi dan harga produk yang beredar di pasar sebagai bahan perbandingan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_3, 'name' => '3.2 Data volume produk yang laku pada periode waktu tertentu untuk setiap jenis produk dikumpulkan.', 'description' => 'Menghimpun data penjualan produk dalam periode tertentu untuk mengetahui tingkat permintaan pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_3, 'name' => '3.3 Data produsen atau supplier setiap jenis produk dikumpulkan.', 'description' => 'Mengidentifikasi produsen atau pemasok produk yang ada di pasar sebagai referensi rantai pasok.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_3, 'name' => '3.4 Data biaya logistik (transportasi dan pergudangan) setiap jenis produk dari setiap produsen/supplier dikumpulkan.', 'description' => 'Mengumpulkan informasi biaya logistik untuk mengetahui struktur biaya distribusi produk.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem3_11_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit3_11,
            'title' => '4. Melakukan analisis data hasil survey pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem3_11_4, 'name' => '4.1 Data hasil survey pasar dikompilasi sesuai dengan tujuan survey.', 'description' => 'Mengelompokkan dan menyusun data hasil survei secara sistematis sesuai tujuan yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_4, 'name' => '4.2 Data hasil survey pasar dianalisis.', 'description' => 'Menganalisis data survei untuk menemukan pola, peluang pasar, dan potensi produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem3_11_4, 'name' => '4.3 Urutan jenis produk dibuat berdasarkan jenis produk yang paling marketable.', 'description' => 'Menyusun peringkat produk berdasarkan tingkat keterjualan dan daya tarik pasar untuk mendukung pengambilan keputusan bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 3: TENAGA PENJUALAN ONLINE');

        // ── Skema 4: PENDAMPING KELEMBAGAAN USAHA ──
        $scheme4 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.004/XI/2023',
            'name' => 'PENDAMPING KELEMBAGAAN USAHA',
            'type' => 'Okupasi',
            'description' => 'Skema Pendamping Kelembagaan Usaha adalah skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan dalam mendampingi dan memperkuat kelembagaan usaha secara profesional dan berkelanjutan. Skema ini mencakup kompetensi dalam melaksanakan dan mengembangkan komunikasi efektif dengan pemangku kepentingan, melakukan pengawasan proses produksi, menentukan jumlah dan keahlian tenaga kerja yang dibutuhkan, menyusun prosedur kerja, peraturan perusahaan dan/atau perjanjian kerja bersama, menghitung biaya investasi, mengurus perizinan usaha industri, serta menyusun laporan tertulis. Sertifikasi ini ditujukan bagi tenaga pendamping yang berperan dalam peningkatan tata kelola, kepatuhan, dan efektivitas operasional kelembagaan usaha sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 2500000,
            'recertification_fee' => 2000000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit4_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.70MKT00.001.2',
            'unit_title' => 'Melaksanakan Komunikasi Efektif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_1,
            'title' => '1. Mengidentifikasi gaya komunikasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_1_1, 'name' => '1.1 Gaya komunikasi pelanggan diidentifikasi sesuai dengan karakter pelanggan.', 'description' => 'Mengamati dan mengenali tipe komunikasi pelanggan (asertif, pasif, agresif, atau pasif-agresif) agar pendekatan komunikasi dapat disesuaikan secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_1_1, 'name' => '1.2 Teknik menghadapi pelanggan dengan gaya komunikasi yang berbeda-beda diterapkan sesuai dengan kebutuhan pelanggan.', 'description' => 'Menerapkan teknik komunikasi yang sesuai untuk setiap tipe pelanggan agar interaksi berjalan efektif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_1_1, 'name' => '1.3 Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur organisasi.', 'description' => 'Menggunakan sarana komunikasi digital seperti telepon, email, dan aplikasi pesan sesuai standar dan aturan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_1,
            'title' => '2. Melaksanakan komunikasi langsung menggunakan bahasa lisan, tulisan, dan bahasa tubuh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_1_2, 'name' => '2.1 Pemilihan kata-kata saat berkomunikasi dilakukan secara cermat sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Memilih bahasa yang sopan, jelas, dan profesional agar pesan mudah dipahami dan tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_1_2, 'name' => '2.2 Bahasa tubuh, bahasa tulisan, dan ekspresi wajah dikendalikan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Mengatur sikap tubuh, ekspresi wajah, serta gaya penulisan untuk mendukung pesan komunikasi secara positif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_1_2, 'name' => '2.3 Tata penampilan profesional dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Menjaga kerapihan dan kesesuaian penampilan agar mencerminkan sikap profesional dan meningkatkan kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.70MKT00.042.1',
            'unit_title' => 'Mengembangkan Pola Komunikasi Efektif kepada Pemangku Kepentingan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_2,
            'title' => '1. Melakukan komunikasi efektif dan konsisten kepada klien dan pelaku usaha',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_2_1, 'name' => '1.1 Komunikasi verbal dan non-verbal digunakan dengan tepat dalam membentuk kesepahaman.', 'description' => 'Menggunakan bahasa lisan, tulisan, dan bahasa tubuh secara selaras untuk memastikan pesan dipahami dengan benar oleh klien dan pelaku usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_1, 'name' => '1.2 Informasi disampaikan dengan jelas, mudah dimengerti dan cara penyampaian yang tepat.', 'description' => 'Menyampaikan informasi secara terstruktur, ringkas, dan sesuai konteks agar tidak menimbulkan salah pengertian.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_1, 'name' => '1.3 Penggunaan peralatan komunikasi digital dan elektronik dioptimalkan sesuai kebutuhan.', 'description' => 'Memanfaatkan media komunikasi digital dan elektronik (telepon, email, aplikasi pesan, platform daring) secara efektif sesuai situasi komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_1, 'name' => '1.4 Informasi atas kebutuhan, permintaan dan isu pelanggan ditindaklanjuti secara responsif.', 'description' => 'Merespons kebutuhan, permintaan, serta permasalahan pelanggan secara cepat, tepat, dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_1, 'name' => '1.5 Komunikasi dan pertukaran informasi dilakukan secara berkala sesuai kebutuhan dan panduan perusahaan.', 'description' => 'Menjaga komunikasi rutin dan berkesinambungan sesuai dengan prosedur dan standar perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_1, 'name' => '1.6 Empati ditunjukkan kepada hambatan dan kesulitan mereka.', 'description' => 'Menunjukkan sikap empati dan kepedulian terhadap kendala yang dialami klien atau pelaku usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_1, 'name' => '1.7 Privasi dan kerahasiaan dijunjung tinggi dalam berkomunikasi.', 'description' => 'Menjaga kerahasiaan data, informasi, dan privasi klien sesuai etika dan kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_2,
            'title' => '2. Melakukan komunikasi dalam tim kerja',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_2_2, 'name' => '2.1 Informasi atas instruksi dan tugas disimak dengan seksama.', 'description' => 'Mendengarkan dan memahami instruksi kerja secara cermat untuk menghindari kesalahan pelaksanaan tugas.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_2, 'name' => '2.2 Terminologi dan jargon digunakan secara tepat baik dalam komunikasi lisan, tertulis dan digital.', 'description' => 'Menggunakan istilah dan jargon kerja yang sesuai agar komunikasi dalam tim berjalan efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_2, 'name' => '2.3 Pola dan hierarki komunikasi dipatuhi dalam berinteraksi dalam tim kerja.', 'description' => 'Mengikuti struktur dan tata komunikasi organisasi dalam berinteraksi dengan atasan maupun rekan kerja.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_2,
            'title' => '3. Merespon hambatan komunikasi yang timbul',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_2_3, 'name' => '3.1 Hambatan dan halangan komunikasi diidentifikasi secara dini untuk tercapainya komunikasi yang efektif.', 'description' => 'Mengidentifikasi potensi gangguan komunikasi sejak awal agar dapat segera ditangani.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_3, 'name' => '3.2 Situasi konflik dan kebuntuan komunikasi diselesaikan dengan keterampilan berkomunikasi yang tepat.', 'description' => 'Menyelesaikan konflik atau miskomunikasi dengan pendekatan komunikasi yang konstruktif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_2_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_2,
            'title' => '4. Melakukan dokumentasi sesuai keperluan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_2_4, 'name' => '4.1 Pengaturan dokumentasi atas komunikasi dipatuhi sesuai prosedur organisasi.', 'description' => 'Mendokumentasikan hasil komunikasi sesuai standar dan prosedur yang berlaku di organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_4, 'name' => '4.2 Agenda dan risalah rapat didokumentasikan dengan tepat.', 'description' => 'Menyusun dan menyimpan agenda serta notulen rapat secara akurat sebagai arsip dan bahan tindak lanjut.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_2_4, 'name' => '4.3 Perhitungan dan kalkulasi rutin dilakukan sesuai kebutuhan.', 'description' => 'Melakukan perhitungan atau pencatatan data numerik yang berkaitan dengan aktivitas komunikasi dan operasional.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.741000.022.01',
            'unit_title' => 'Melakukan pengawasan jalannya proses produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_3,
            'title' => '1. Menyiapkan pelaksanaan pengawasan jalannya proses produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_3_1, 'name' => '1.1 Pengetahuan tentang manajemen supervisi dan manajemen produksi dijelaskan.', 'description' => 'Menjelaskan konsep dasar supervisi dan manajemen produksi sebagai landasan dalam melakukan pengawasan proses produksi secara efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_3_1, 'name' => '1.2 Rencana produksi dan rencana kerja di ruang produksi diperiksa.', 'description' => 'Memeriksa kesesuaian rencana produksi dan rencana kerja dengan target, kapasitas, serta sumber daya yang tersedia.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_3_1, 'name' => '1.3 Prosedur pengawasan proses produksi disiapkan.', 'description' => 'Menyiapkan prosedur pengawasan yang mencakup tahapan, metode, dan standar pengendalian proses produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_3,
            'title' => '2. Melakukan pengawasan jalannya proses produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_3_2, 'name' => '2.1 Pengawasan proses produksi dilakukan sesuai prosedur.', 'description' => 'Melaksanakan pengawasan langsung terhadap proses produksi berdasarkan prosedur dan standar yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_3_2, 'name' => '2.2 Perbaikan terhadap unit proses yang tidak lancar dilakukan sesuai prosedur.', 'description' => 'Mengidentifikasi hambatan produksi dan melakukan tindakan korektif sesuai ketentuan untuk menjaga kelancaran proses.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_3_2, 'name' => '2.3 Penggunaan unit proses diatur kembali sesuai prosedur.', 'description' => 'Menyesuaikan kembali penggunaan mesin, peralatan, atau tenaga kerja agar proses produksi berjalan optimal.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_3_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_3,
            'title' => '3. Melaporkan pelaksanaan pengawasan jalannya proses produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_3_3, 'name' => '3.1 Semua kegiatan pengawasan proses produksi yang dilakukan dicatat.', 'description' => 'Mendokumentasikan seluruh aktivitas pengawasan sebagai bahan evaluasi dan arsip produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_3_3, 'name' => '3.2 Kegiatan pengawasan proses produksi dilaporkan kepada pihak terkait.', 'description' => 'Menyusun dan menyampaikan laporan hasil pengawasan kepada atasan atau pihak yang berkepentingan sesuai prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.741000.007.01',
            'unit_title' => 'Menentukan jumlah dan keahlian tenaga kerja yang dibutuhkan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_4,
            'title' => '1. Menyiapkan bagan proses produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_4_1, 'name' => '1.1 Tata cara penanganan bahan baku dan bahan pembantu untuk setiap jenis proses dijelaskan.', 'description' => 'Menjelaskan metode dan prosedur penanganan bahan baku serta bahan pembantu agar sesuai standar mutu, keamanan, dan efisiensi proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_1, 'name' => '1.2 Urutan proses produksi disiapkan.', 'description' => 'Menyusun tahapan proses produksi secara sistematis mulai dari bahan baku hingga produk akhir.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_1, 'name' => '1.3 Tata cara pengoperasian mesin dan peralatan dijelaskan.', 'description' => 'Menjelaskan prosedur penggunaan mesin dan peralatan produksi sesuai petunjuk teknis dan standar keselamatan kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_1, 'name' => '1.4 Tata cara penanganan produk antara dan produk akhir dijelaskan.', 'description' => 'Menjelaskan cara penanganan, penyimpanan sementara, dan pengamanan produk antara serta produk akhir agar kualitas tetap terjaga.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_4,
            'title' => '2. Menentukan kebutuhan tenaga kerja penanganan bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_4_2, 'name' => '2.1 Keahlian tenaga kerja yang menangani bahan baku dan bahan pembantu untuk setiap jenis proses dijelaskan.', 'description' => 'Menjelaskan kompetensi dan keterampilan tenaga kerja yang dibutuhkan sesuai karakteristik proses dan jenis bahan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_2, 'name' => '2.2 Waktu yang diperlukan untuk penanganan bahan baku dan bahan pembantu setiap volume tertentu untuk setiap jenis proses dihitung.', 'description' => 'Menghitung waktu kerja yang dibutuhkan berdasarkan volume produksi dan jenis proses untuk efisiensi tenaga kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_2, 'name' => '2.3 Jumlah dan keahlian tenaga kerja untuk menangani bahan baku dan bahan pembantu untuk setiap jenis proses ditentukan.', 'description' => 'Menentukan jumlah personel dan tingkat keahlian yang diperlukan agar proses produksi berjalan optimal.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_4,
            'title' => '3. Menentukan kebutuhan tenaga kerja penanganan produk antara dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_4_3, 'name' => '3.1 Keahlian tenaga kerja yang menangani produk antara dan produk akhir dijelaskan.', 'description' => 'Menjelaskan keterampilan dan kompetensi tenaga kerja dalam menangani produk antara dan produk akhir sesuai standar mutu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_3, 'name' => '3.2 Waktu yang diperlukan untuk penanganan produk antara dan produk akhir setiap volume tertentu dihitung.', 'description' => 'Menghitung kebutuhan waktu kerja berdasarkan volume produk untuk mendukung kelancaran alur produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_3, 'name' => '3.3 Jumlah dan keahlian tenaga kerja untuk menangani produk antara dan produk akhir ditentukan.', 'description' => 'Menentukan jumlah tenaga kerja dan keahliannya sesuai beban kerja dan target produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_4_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_4,
            'title' => '4. Menentukan kebutuhan tenaga kerja pengoperasian mesin dan peralatan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_4_4, 'name' => '4.1 Keahlian tenaga kerja yang mengoperasikan mesin dan peralatan dijelaskan.', 'description' => 'Menjelaskan kompetensi teknis yang diperlukan untuk mengoperasikan mesin dan peralatan produksi dengan aman dan efisien.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_4, 'name' => '4.2 Jumlah tenaga kerja yang mengoperasikan setiap mesin dan setiap peralatan dijelaskan berdasarkan buku panduan masing-masing mesin dan peralatan.', 'description' => 'Menentukan kebutuhan operator mesin sesuai spesifikasi dan ketentuan pada manual penggunaan mesin dan peralatan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_4_4, 'name' => '4.3 Jumlah tenaga kerja yang mengoperasikan mesin dan peralatan dihitung berdasarkan jumlah mesin dan peralatan yang ada.', 'description' => 'Menghitung total kebutuhan tenaga kerja operator berdasarkan jumlah mesin dan peralatan yang digunakan dalam proses produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.741000.013.01',
            'unit_title' => 'Membuat prosedur kerja untuk setiap tugas',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_5,
            'title' => '1. Menyiapkan pembuatan prosedur kerja',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_5_1, 'name' => '1.1 Tahapan proses kerja diinventarisasi.', 'description' => 'Mengidentifikasi dan mencatat seluruh tahapan proses kerja yang ada dalam organisasi sebagai dasar penyusunan prosedur kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_5_1, 'name' => '1.2 Persyaratan pada setiap tahapan diinventarisasi.', 'description' => 'Menginventarisasi persyaratan teknis, administratif, dan keselamatan kerja pada setiap tahapan proses.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_5_1, 'name' => '1.3 Setiap tugas dalam tahapan proses kerja diinventarisasi.', 'description' => 'Mencatat seluruh tugas yang terdapat pada setiap tahapan proses kerja secara rinci dan sistematis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_5_1, 'name' => '1.4 Tugas-tugas yang harus dilaksanakan setiap personil dalam perusahaan diinventarisasi.', 'description' => 'Mengidentifikasi pembagian tugas dan tanggung jawab setiap personil untuk memastikan kejelasan peran kerja.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_5,
            'title' => '2. Membuat prosedur kerja',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_5_2, 'name' => '2.1 Prosedur kerja untuk setiap tugas dibuat.', 'description' => 'Menyusun prosedur kerja tertulis yang jelas dan terstandar untuk setiap tugas yang telah diinventarisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_5_2, 'name' => '2.2 Prosedur kerja diverifikasi di lapangan.', 'description' => 'Menguji dan memastikan prosedur kerja dapat diterapkan secara nyata dan efektif di lingkungan kerja.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_5,
            'title' => '3. Mensosialisasikan prosedur kerja',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_5_3, 'name' => '3.1 Prosedur kerja untuk setiap personil disiapkan dalam bentuk tertulis (print-out) sesuai prosedur.', 'description' => 'Menyediakan dokumen prosedur kerja tertulis agar mudah dipahami dan digunakan oleh seluruh personil.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.70SDM01.043.2',
            'unit_title' => 'Menyusun peraturan perusahaan dan/atau perjanjian kerja bersama',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_6,
            'title' => '1. Menyusun peraturan perusahaan dan atau perjanjian kerja bersama',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_6_1, 'name' => '1.1 Konten peraturan perusahaan dan atau perjanjian kerja bersama diidentifikasi sesuai dengan kebutuhan organisasi dan masukan dari pemangku kepentingan.', 'description' => 'Deskripsi / Penjelasan KUK', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_6_1, 'name' => '1.2 Peraturan perusahaan dan atau perjanjian kerja bersama disusun atau dirundingkan untuk kemudian diajukan ke instansi yang berwenang untuk mendapatkan pengesahan dan bukti terdaftar.', 'description' => 'Mengidentifikasi materi, pasal, dan ketentuan peraturan perusahaan atau perjanjian kerja bersama berdasarkan kebutuhan organisasi serta masukan dari pihak terkait.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_6,
            'title' => '2. Menerapkan peraturan perusahaan dan atau perjanjian kerja bersama',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_6_2, 'name' => '2.1 Peraturan perusahaan dan atau perjanjian kerja bersama yang telah disusun dan atau disepakati diajukan ke instansi yang berwenang untuk mendapatkan pengesahan dan bukti terdaftar.', 'description' => 'Menyusun atau merundingkan peraturan perusahaan atau perjanjian kerja bersama secara formal agar dapat diajukan dan disahkan oleh instansi yang berwenang.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_6_2, 'name' => '2.2 Program sosialisasi peraturan perusahaan dan atau perjanjian kerja bersama yang telah disahkan dan atau didaftarkan dilaksanakan sesuai standar operasional prosedur yang berlaku.', 'description' => 'Mengajukan dokumen peraturan perusahaan atau perjanjian kerja bersama yang telah disepakati untuk memperoleh pengesahan dan bukti pendaftaran resmi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.741000.003.01',
            'unit_title' => 'Menghitung biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_7,
            'title' => '1. Menyiapkan penghitungan biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_7_1, 'name' => '1.1 Lokasi produksi serta harga tanah dan atau bangunannya diidentifikasi', 'description' => 'Mengidentifikasi lokasi usaha serta mengetahui harga tanah dan/atau bangunan sebagai dasar perhitungan investasi awal.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_1, 'name' => '1.2 Produsen/supplier mesin dan peralatan, serta harga setiap mesin dan peralatan diidentifikasi', 'description' => 'Mengumpulkan data produsen atau supplier mesin dan peralatan beserta harga masing-masing sebagai komponen investasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_1, 'name' => '1.3 Kemudahan pengadaan mesin dan peralatan dari setiap produsen/supplier diidentifikasi', 'description' => 'Menilai tingkat kemudahan pengadaan mesin dan peralatan, termasuk waktu pengiriman dan ketersediaan suku cadang.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_7,
            'title' => '2. Menghitung biaya investasi untuk setiap jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_7_2, 'name' => '2.1 Lokasi produksi ditentukan berdasarkan Aturan Rencana Tata Ruang dan Wilayah yang berlaku', 'description' => 'Menentukan lokasi produksi yang sesuai dengan peraturan tata ruang dan ketentuan pemerintah setempat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_2, 'name' => '2.2 Harga tanah dan bangunan dihitung berdasarkan harga pasar', 'description' => 'Menghitung nilai investasi tanah dan bangunan berdasarkan harga pasar yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_2, 'name' => '2.3 Mesin dan peralatan yang akan digunakan untuk setiap produk diidentifikasi', 'description' => 'Menentukan jenis mesin dan peralatan yang dibutuhkan untuk memproduksi setiap jenis produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_2, 'name' => '2.4 Harga mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'description' => 'Menghitung biaya mesin dan peralatan berdasarkan daftar harga resmi atau penawaran dari distributor.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_2, 'name' => '2.5 Biaya instalasi mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'description' => 'Menghitung biaya pemasangan, instalasi, dan uji coba mesin sebagai bagian dari biaya investasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_7_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_7,
            'title' => '3. Melakukan analisis biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_7_3, 'name' => '3.1 Urutan biaya investasi dibuat dari yang nilainya terendah', 'description' => 'Menyusun daftar biaya investasi dari yang paling rendah untuk mengetahui alternatif investasi yang paling efisien.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_7_3, 'name' => '3.2 Urutan biaya investasi dibuat berdasarkan tingkat kemudahan pengadaannya', 'description' => 'Menganalisis biaya investasi berdasarkan kemudahan pengadaan untuk meminimalkan risiko keterlambatan usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_8 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'M.741000.008.01',
            'unit_title' => 'Melakukan pengurusan perijinan usaha industri',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_8_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_8,
            'title' => '1. Menyiapkan pengurusan perijinan usaha industri',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_8_1, 'name' => '1.1 Instansi pemerintah yang terkait dengan pengurusan perijinan usaha industri diidentifikasi', 'description' => 'Mengidentifikasi instansi pemerintah yang berwenang dalam penerbitan izin usaha industri sesuai dengan jenis dan skala usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_8_1, 'name' => '1.2 Prosedur dan persyaratan pengurusan perijinan usaha industri dijelaskan', 'description' => 'Menjelaskan alur proses, dokumen, serta persyaratan administrasi yang harus dipenuhi dalam pengurusan izin usaha industri.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_8_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_8,
            'title' => '2. Melakukan pengurusan perijinan usaha industri',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_8_2, 'name' => '2.1 Persyaratan pengurusan perijinan usaha industri disiapkan', 'description' => 'Menyiapkan seluruh dokumen dan persyaratan administrasi yang dibutuhkan untuk pengajuan izin usaha industri.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_8_2, 'name' => '2.2 Pengurusan perijinan usaha industri dilakukan sesuai prosedur', 'description' => 'Melaksanakan proses pengurusan izin usaha industri dengan mengikuti prosedur resmi hingga izin diterbitkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit4_9 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme4,
            'unit_code' => 'N.821100.006.01',
            'unit_title' => 'Membuat Laporan Tertulis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem4_9_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_9,
            'title' => '1. Mempersiapkan dokumen laporan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_9_1, 'name' => '1.1 Data yang akan digunakan di dalam pembuatan laporan diidentifikasi.', 'description' => 'Mengidentifikasi dan mengumpulkan data yang relevan sebagai bahan dasar penyusunan laporan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_1, 'name' => '1.2 Perangkat keras dan perlengkapan pendukung serta peralatan pendukung lainnya diidentifikasi.', 'description' => 'Menentukan kebutuhan perangkat keras dan perlengkapan pendukung agar proses pembuatan laporan berjalan lancar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_1, 'name' => '1.3 Perangkat lunak yang akan digunakan untuk pembuatan laporan diidentifikasi sesuai kebutuhan.', 'description' => 'Menentukan aplikasi atau perangkat lunak yang sesuai dengan jenis dan tujuan laporan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_9_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_9,
            'title' => '2. Menentukan jenis laporan yang akan dibuat',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_9_2, 'name' => '2.1 Jenis laporan yang akan dibuat diidentifikasi sesuai kebutuhan.', 'description' => 'Menentukan jenis laporan berdasarkan tujuan, pengguna, dan kebutuhan informasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_2, 'name' => '2.2 Dokumen baru dibuat sesuai bentuk laporan.', 'description' => 'Membuat dokumen baru yang sesuai dengan struktur dan karakteristik jenis laporan yang dipilih.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_2, 'name' => '2.3 Format dan layout dokumen dibuat.', 'description' => 'Menyusun tata letak dokumen agar rapi, sistematis, dan mudah dibaca.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_2, 'name' => '2.4 Template atau formulir yang akan digunakan ditetapkan sesuai kebutuhan.', 'description' => 'Menentukan penggunaan template atau formulir standar untuk menjaga konsistensi laporan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_2, 'name' => '2.5 Format dokumen seperti huruf, ukuran, bentuk, tabulasi, bullets, dan nomor halaman ditetapkan.', 'description' => 'Mengatur format penulisan dokumen agar sesuai dengan standar organisasi dan kebutuhan pelaporan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_9_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_9,
            'title' => '3. Menulis laporan tertulis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_9_3, 'name' => '3.1 Laporan dibuat sesuai jenis laporan.', 'description' => 'Menyusun isi laporan berdasarkan struktur dan ketentuan dari jenis laporan yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_3, 'name' => '3.2 Teks dan gambar ditambahkan dari dokumen lain ke dalam laporan sesuai kebutuhan.', 'description' => 'Mengintegrasikan teks, gambar, atau ilustrasi pendukung dari sumber lain secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_3, 'name' => '3.3 Tabel, makro, dan footnote dibuat sesuai kebutuhan.', 'description' => 'Menambahkan elemen pendukung seperti tabel, makro, dan catatan kaki untuk memperjelas isi laporan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_3, 'name' => '3.4 Suku kata dan tata bahasa digunakan sesuai kebutuhan.', 'description' => 'Menggunakan bahasa yang baik, benar, dan sesuai kaidah untuk meningkatkan kualitas laporan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_3, 'name' => '3.5 Dokumen disimpan pada media penyimpanan sesuai prosedur.', 'description' => 'Menyimpan dokumen laporan dengan aman dan terstruktur sesuai prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem4_9_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit4_9,
            'title' => '4. Menyediakan laporan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem4_9_4, 'name' => '4.1 Fungsi preview digunakan untuk melihat tampilan dokumen sebelum dicetak.', 'description' => 'Memeriksa tampilan akhir dokumen guna memastikan kesesuaian format sebelum pencetakan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_4, 'name' => '4.2 Fungsi pencetakan digunakan pada saat mencetak dokumen sesuai kebutuhan pelaporan.', 'description' => 'Mencetak dokumen laporan sesuai jumlah dan spesifikasi yang dibutuhkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem4_9_4, 'name' => '4.3 Dokumen diserahkan kepada yang berkepentingan.', 'description' => 'Menyerahkan laporan kepada pihak terkait sesuai ketentuan dan jalur distribusi yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 4: PENDAMPING KELEMBAGAAN USAHA');

        // ── Skema 5: MOTIVATOR KINERJA TIM PENJUALAN ──
        $scheme5 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.005/XI/2023',
            'name' => 'MOTIVATOR KINERJA TIM PENJUALAN',
            'type' => 'Okupasi',
            'description' => 'Skema Motivator Kinerja Tim Penjualan merupakan skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan dalam memotivasi, membina, dan meningkatkan kinerja tim penjualan secara berkelanjutan. Skema ini mencakup kompetensi dalam melaksanakan komunikasi efektif, mengembangkan keterampilan dan kerja sama anggota tim penjual, memberikan bimbingan serta dukungan peningkatan kualitas hidup, mengelola stres dalam dinamika tim penjualan, serta memimpin program transformasi dalam penjualan dan layanan. Sertifikasi ini ditujukan bagi individu yang berperan sebagai motivator, pembina, atau pemimpin tim penjualan dalam mendorong pencapaian target, peningkatan layanan, dan kinerja tim yang optimal sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 3500000,
            'recertification_fee' => 2750000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit5_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme5,
            'unit_code' => 'M.70MKT00.001.2',
            'unit_title' => 'Melaksanakan Komunikasi Efektif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem5_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_1,
            'title' => '1. Mengidentifikasi gaya komunikasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_1_1, 'name' => '1.1 Gaya komunikasi pelanggan diidentifikasi sesuai dengan karakter pelanggan.', 'description' => 'Mengamati dan mengenali tipe komunikasi pelanggan (asertif, pasif, agresif, atau pasif-agresif) agar pendekatan komunikasi dapat disesuaikan secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_1_1, 'name' => '1.2 Teknik menghadapi pelanggan dengan gaya komunikasi yang berbeda-beda diterapkan sesuai dengan kebutuhan pelanggan.', 'description' => 'Menerapkan teknik komunikasi yang sesuai untuk setiap tipe pelanggan agar interaksi berjalan efektif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_1_1, 'name' => '1.3 Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur organisasi.', 'description' => 'Menggunakan sarana komunikasi digital seperti telepon, email, dan aplikasi pesan sesuai standar dan aturan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_1,
            'title' => '2. Melaksanakan komunikasi langsung menggunakan bahasa lisan, tulisan, dan bahasa tubuh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_1_2, 'name' => '2.1 Pemilihan kata-kata saat berkomunikasi dilakukan secara cermat sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Memilih bahasa yang sopan, jelas, dan profesional agar pesan mudah dipahami dan tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_1_2, 'name' => '2.2 Bahasa tubuh, bahasa tulisan, dan ekspresi wajah dikendalikan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Mengatur sikap tubuh, ekspresi wajah, serta gaya penulisan untuk mendukung pesan komunikasi secara positif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_1_2, 'name' => '2.3 Tata penampilan profesional dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Menjaga kerapihan dan kesesuaian penampilan agar mencerminkan sikap profesional dan meningkatkan kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit5_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme5,
            'unit_code' => 'M.70MKT00.045.1',
            'unit_title' => 'Mengembangkan Keterampilan dan Kerjasama Anggota Tim Penjual',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem5_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_2,
            'title' => '1. Merencanakan pelatihan dan pengembangan individu dan tim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_2_1, 'name' => '1.1 Kebutuhan pembelajaran dan tindakan direncanakan dengan anggota tim yang relevan.', 'description' => 'Deskripsi / Penjelasan KUK', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_2_1, 'name' => '1.2 Rencana pembelajaran dikonfirmasi terhadap kebutuhan pengembangan individu dan kelompok.', 'description' => 'Mengidentifikasi dan merencanakan kebutuhan pembelajaran bersama anggota tim terkait untuk mendukung pengembangan kompetensi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_2_1, 'name' => '1.3 Evaluasi kinerja pribadi dan area perbaikan dievaluasi secara mandiri.', 'description' => 'Memastikan rencana pembelajaran selaras dengan kebutuhan pengembangan individu maupun tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_2_1, 'name' => '1.4 Umpan balik tentang kinerja anggota tim dikumpulkan dari sumber yang relevan.', 'description' => 'Melakukan penilaian mandiri terhadap kinerja pribadi untuk mengidentifikasi kekuatan dan area yang perlu ditingkatkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_2,
            'title' => '2. Menentukan metode pengembangan pelatihan individu dan tim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_2_2, 'name' => '2.1 Tujuan dan sasaran program pembelajaran dan pengembangan diidentifikasi sesuai dengan standar kompetensi industri yang relevan.', 'description' => 'Mengumpulkan masukan kinerja anggota tim dari atasan, rekan kerja, atau sumber lain yang relevan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_2_2, 'name' => '2.2 Metode penyampaian pembelajaran dipastikan sesuai dengan tujuan pembelajaran, gaya belajar peserta, dan ketersediaan peralatan dan sumber daya.', 'description' => 'Menetapkan tujuan dan sasaran program pelatihan berdasarkan standar kompetensi yang berlaku di industri.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_2_2, 'name' => '2.3 Kesempatan belajar di tempat kerja serta bantuan pembinaan dan pendampingan disediakan untuk pencapaian kompetensi individu dan tim.', 'description' => 'Menentukan metode pelatihan yang paling efektif dengan mempertimbangkan kebutuhan peserta dan sumber daya yang tersedia.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_2_2, 'name' => '2.4 Peluang pengembangan yang tergabung dari berbagai kegiatan dan materi pendukung diterapkan sesuai dengan pencapaian kompetensi yang teridentifikasi.', 'description' => 'Menyediakan pembelajaran berbasis kerja serta mentoring dan coaching untuk mendukung pencapaian kompetensi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit5_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme5,
            'unit_code' => 'M.70MKT00.044.1',
            'unit_title' => 'Memberikan Bimbingan dan Peningkatan Kualitas Hidup',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem5_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_3,
            'title' => '1. Merumuskan kebutuhan bimbingan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_3_1, 'name' => '1.1 Kebutuhan bimbingan anggota tim diidentifikasi berdasarkan rencana pengembangan diri, termasuk kebutuhan spesifiknya.', 'description' => 'Mengidentifikasi kebutuhan bimbingan anggota tim dengan mengacu pada rencana pengembangan diri dan kebutuhan khusus masing-masing individu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_1, 'name' => '1.2 Harapan dan tujuan bimbingan didiskusikan dengan peserta dan pihak yang relevan.', 'description' => 'Mendiskusikan ekspektasi dan tujuan bimbingan bersama peserta dan pemangku kepentingan terkait agar terdapat kesamaan persepsi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_1, 'name' => '1.3 Sasaran spesifik bimbingan ditentukan berdasarkan penilaian kesiapan, kemampuan, dan tingkat kompetensi anggota tim.', 'description' => 'Menetapkan sasaran bimbingan yang terukur berdasarkan hasil penilaian kompetensi anggota tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_1, 'name' => '1.4 Hambatan dan risiko terkait pelaksanaan bimbingan diidentifikasi sesuai prosedur organisasi.', 'description' => 'Mengidentifikasi potensi hambatan dan risiko pelaksanaan bimbingan untuk meminimalkan dampak negatif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_1, 'name' => '1.5 Sumber daya untuk program bimbingan ditentukan berdasarkan kesiapan, kemampuan, dan tingkat kompetensi anggota tim.', 'description' => 'Menentukan sumber daya yang diperlukan, seperti waktu, tenaga, dan fasilitas, sesuai kebutuhan peserta.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_1, 'name' => '1.6 Metode dan pendekatan bimbingan ditetapkan sesuai dengan karakteristik anggota tim.', 'description' => 'Menetapkan metode bimbingan yang sesuai dengan karakter, gaya belajar, dan kebutuhan anggota tim.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_3,
            'title' => '2. Mengembangkan rencana bimbingan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_3_2, 'name' => '2.1 Ruang lingkup dan batasan program bimbingan ditentukan sesuai prosedur organisasi.', 'description' => 'Menetapkan cakupan dan batasan program bimbingan agar selaras dengan kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_2, 'name' => '2.2 Rencana dan tahapan bimbingan disusun berdasarkan kebutuhan, tujuan, dan sasaran spesifik.', 'description' => 'Menyusun rencana dan tahapan pelaksanaan bimbingan secara sistematis dan terstruktur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_2, 'name' => '2.3 Kesepakatan atas aturan dasar, hubungan, dan cara pemantauan program didiskusikan bersama.', 'description' => 'Menyepakati aturan main, hubungan kerja, dan mekanisme pemantauan antara pembimbing dan peserta.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_2, 'name' => '2.4 Hal-hal yang berkaitan dengan kerahasiaan dijaga sepanjang program bimbingan berlangsung.', 'description' => 'Menjaga kerahasiaan informasi peserta selama proses bimbingan sesuai etika dan prosedur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_2, 'name' => '2.5 Rencana bimbingan didokumentasikan secara detail dan format baku sesuai ketentuan organisasi.', 'description' => 'Mendokumentasikan rencana bimbingan secara lengkap sesuai standar dan format organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_3_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_3,
            'title' => '3. Melakukan bimbingan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_3_3, 'name' => '3.1 Percaya diri dan harga diri anggota tim dibangun selama proses bimbingan atas dasar kepercayaan dan saling menghormati.', 'description' => 'Membangun rasa percaya diri peserta melalui hubungan bimbingan yang saling menghargai.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_3, 'name' => '3.2 Kesepakatan sesuai peran dan tanggung jawab diputuskan berdasarkan kebutuhan organisasi.', 'description' => 'Menetapkan peran dan tanggung jawab pembimbing dan peserta sesuai kepentingan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_3, 'name' => '3.3 Instruksi, petunjuk, informasi, dan panduan dikomunikasikan dengan tatakrama yang tepat.', 'description' => 'Menyampaikan arahan dan informasi secara jelas, sopan, dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_3, 'name' => '3.4 Dukungan dalam pemecahan masalah dan pengambilan keputusan diberikan secara tepat waktu.', 'description' => 'Memberikan dukungan yang dibutuhkan peserta untuk menyelesaikan masalah dan mengambil keputusan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_3, 'name' => '3.5 Jejaring kerja pribadi dan profesional yang relevan diperkenalkan kepada anggota tim.', 'description' => 'Memperkenalkan jaringan kerja yang bermanfaat untuk pengembangan kompetensi peserta.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_3_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_3,
            'title' => '4. Memantau proses bimbingan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_3_4, 'name' => '4.1 Perilaku yang kurang tepat terkait bimbingan didiskusikan untuk tindakan perbaikan.', 'description' => 'Membahas perilaku yang tidak sesuai untuk dilakukan perbaikan secara konstruktif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_4, 'name' => '4.2 Masukan dan umpan balik didapatkan secara berkala sepanjang program berlangsung.', 'description' => 'Mengumpulkan umpan balik secara rutin untuk meningkatkan efektivitas bimbingan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_4, 'name' => '4.3 Tempat, lingkungan, dan ketersediaan sumber daya dievaluasi secara berkala untuk tindakan perbaikan.', 'description' => 'Mengevaluasi faktor pendukung bimbingan agar proses berjalan optimal.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_4, 'name' => '4.4 Kemajuan, isu, dan hambatan dimonitor secara berkala untuk tindakan lanjut.', 'description' => 'Memantau perkembangan dan hambatan peserta untuk menentukan tindak lanjut.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_4, 'name' => '4.5 Perubahan dan tindakan perbaikan dikomunikasikan kepada peserta dan pihak relevan.', 'description' => 'Menginformasikan perubahan dan perbaikan kepada semua pihak terkait.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_4, 'name' => '4.6 Penyelesaian bimbingan didiskusikan ketika tujuan dan sasaran spesifik telah tercapai.', 'description' => 'Mendiskusikan penutupan program bimbingan setelah tujuan tercapai.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_3_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_3,
            'title' => '5. Mengevaluasi proses bimbingan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_3_5, 'name' => '5.1 Masukan atas kualitas bimbingan, efektivitas, dan peningkatan diri peserta didapatkan dari berbagai pihak relevan.', 'description' => 'Mengumpulkan evaluasi dari berbagai pihak mengenai kualitas dan dampak bimbingan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_5, 'name' => '5.2 Masukan dari berbagai pihak dan pengalaman pribadi dievaluasi untuk peluang perbaikan.', 'description' => 'Mengevaluasi masukan dan pengalaman untuk peningkatan program di masa depan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_5, 'name' => '5.3 Tren dan metode mutakhir dalam bimbingan dicari untuk perbaikan.', 'description' => 'Mengidentifikasi metode dan tren terbaru untuk meningkatkan kualitas bimbingan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_3_5, 'name' => '5.4 Program bimbingan diperbaiki berdasarkan masukan dan peluang perbaikan.', 'description' => 'Melakukan penyempurnaan program bimbingan berdasarkan hasil evaluasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit5_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme5,
            'unit_code' => 'M.70MKT00.041.1',
            'unit_title' => 'Mengelola Stres Dalam Dinamika Tim Penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem5_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_4,
            'title' => '1. Mengembangkan dan menerapkan strategi manajemen stres individu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_4_1, 'name' => '1.1 Tanda-tanda dan sumber stres dikenali sedini mungkin.', 'description' => 'Mengidentifikasi gejala dan sumber stres secara dini agar dapat ditangani sebelum berdampak pada kinerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_1, 'name' => '1.2 Teknik pengelolaan stres yang sesuai diterapkan secara tepat.', 'description' => 'Menerapkan teknik manajemen stres yang relevan sesuai kondisi dan kebutuhan individu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_1, 'name' => '1.3 Stamina dan ketahanan tubuh pribadi dijaga secara berkala.', 'description' => 'Menjaga kebugaran fisik dan stamina melalui kebiasaan hidup sehat secara konsisten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_1, 'name' => '1.4 Keseimbangan dilaksanakan antara pekerjaan dan kehidupan pribadi.', 'description' => 'Menjaga keseimbangan antara tuntutan pekerjaan dan kehidupan pribadi (work–life balance).', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_1, 'name' => '1.5 Manajemen stres dan waktu dilaksanakan secara konsisten.', 'description' => 'Mengelola stres dan waktu secara disiplin dan berkelanjutan dalam aktivitas kerja.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_4,
            'title' => '2. Mengembangkan strategi teknik dan manajemen stres di dalam tim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_4_2, 'name' => '2.1 Potensi sumber kelelahan dan stres di lingkungan kerja ditentukan berdasarkan kondisi kerja.', 'description' => 'Mengidentifikasi faktor lingkungan kerja yang berpotensi menyebabkan stres dan kelelahan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_2, 'name' => '2.2 Teknik pengelolaan stres dalam tim dikembangkan sesuai kebijakan organisasi.', 'description' => 'Mengembangkan teknik manajemen stres tim yang selaras dengan kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_2, 'name' => '2.3 Teknik pencapaian KPI dan prioritas pekerjaan dikembangkan secara berkala.', 'description' => 'Menyusun teknik pencapaian target kerja dan prioritas untuk mengurangi tekanan kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_2, 'name' => '2.4 Dukungan diberikan dalam pengelolaan stres pada anggota tim.', 'description' => 'Memberikan dukungan emosional dan praktis kepada anggota tim dalam mengelola stres.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_2, 'name' => '2.5 Teknik pengurangan, pengelolaan, dan penanganan stres dalam tim diterapkan secara konsisten.', 'description' => 'Menerapkan strategi penanganan stres tim secara berkesinambungan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_2, 'name' => '2.6 Teknik pengelolaan stamina tubuh diterapkan secara efektif.', 'description' => 'Mendorong penerapan teknik menjaga stamina dan kesehatan anggota tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_2, 'name' => '2.7 Teknik pemulihan anggota tim diidentifikasi dari situasi stres atau sulit.', 'description' => 'Mengidentifikasi dan menerapkan teknik pemulihan bagi anggota tim yang mengalami stres berat.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_4,
            'title' => '3. Menerapkan teknik manajemen stres di dalam tim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_4_3, 'name' => '3.1 Rutinitas kerja dan cara-cara terstruktur diterapkan untuk pengelolaan stres dan kelelahan.', 'description' => 'Menerapkan pola kerja terstruktur untuk meminimalkan stres dan kelelahan tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_3, 'name' => '3.2 Teknik pengelolaan waktu yang tepat diterapkan secara praktis.', 'description' => 'Menerapkan manajemen waktu yang efektif dalam aktivitas kerja tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_3, 'name' => '3.3 Program pelatihan dan pengembangan dilaksanakan sesuai jadwal kerja.', 'description' => 'Menyelenggarakan pelatihan dan pengembangan individu maupun tim secara terencana.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_3, 'name' => '3.4 Cara praktis dan teknik pengelolaan waktu dievaluasi secara teratur.', 'description' => 'Mengevaluasi efektivitas teknik manajemen waktu secara berkala.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_3, 'name' => '3.5 Kemauan tim dikembangkan terhadap sumber dukungan internal dan eksternal.', 'description' => 'Mendorong tim untuk memanfaatkan dukungan internal dan eksternal secara berkelanjutan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_4_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_4,
            'title' => '4. Mengembangkan strategi membangun tim dan moral',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_4_4, 'name' => '4.1 Teknik pelibatan dan pengembangan anggota tim dikembangkan secara berkala.', 'description' => 'Mengembangkan metode pelibatan anggota tim untuk meningkatkan motivasi dan kinerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_4, 'name' => '4.2 Teknik komunikasi dalam tim diterapkan secara efektif.', 'description' => 'Menerapkan komunikasi yang terbuka, jelas, dan efektif dalam tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_4, 'name' => '4.3 Rapat tim dikelola secara efektif.', 'description' => 'Mengelola rapat tim secara efisien dan berorientasi pada solusi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_4, 'name' => '4.4 Dukungan lingkungan tim yang suportif dan responsif dikembangkan.', 'description' => 'Menciptakan lingkungan kerja tim yang saling mendukung dan responsif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_4, 'name' => '4.5 Pekerjaan didelegasikan untuk pemberdayaan anggota tim.', 'description' => 'Mendelegasikan tugas untuk meningkatkan kepercayaan diri dan kompetensi anggota tim.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_4_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_4,
            'title' => '5. Memantau dan mengukur tingkat stres di dalam tim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_4_5, 'name' => '5.1 Kinerja staf dan individu terhadap KPI dipantau secara berkala.', 'description' => 'Memantau pencapaian kinerja individu dan tim sebagai indikator tingkat stres kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_5, 'name' => '5.2 Variasi dan kesulitan kerja diidentifikasi sedini mungkin.', 'description' => 'Mengidentifikasi perubahan dan kesulitan kerja yang berpotensi menimbulkan stres.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_5, 'name' => '5.3 Situasi tingkat stres pada anggota tim dipantau secara berkala.', 'description' => 'Memantau kondisi stres anggota tim secara rutin.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_5, 'name' => '5.4 Tanda-tanda perilaku stres ditanggapi secara positif.', 'description' => 'Menanggapi gejala stres dengan pendekatan suportif dan solutif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_4_5, 'name' => '5.5 Rencana dan hasil diskusi atas tindakan perbaikan didiskusikan dengan anggota tim terkait.', 'description' => 'Mendiskusikan rencana tindak lanjut dan hasil evaluasi bersama anggota tim yang relevan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit5_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme5,
            'unit_code' => 'M.70MKT00.050.1',
            'unit_title' => 'Memimpin Program Transformasi dalam Penjualan dan Layanan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem5_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_5,
            'title' => '1. Mendiagnosis kebutuhan transformasi penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_5_1, 'name' => '1.1 Kebutuhan, peluang dan kelayakan program transformasi dianalisis dari sudut pandang internal dan eksternal.', 'description' => 'Menganalisis kebutuhan dan peluang transformasi penjualan dengan mempertimbangkan kondisi internal organisasi dan faktor eksternal pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_1, 'name' => '1.2 Potensi program transformasi dianalisis keselarasan­nya terhadap strategi bisnis, visi dan misi organisasi.', 'description' => 'Menilai kesesuaian program transformasi dengan arah strategis, visi, dan misi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_1, 'name' => '1.3 Dampak transformasi tingkat kinerja atas program dianalisis sesuai kebijakan organisasi.', 'description' => 'Menganalisis dampak transformasi terhadap peningkatan kinerja organisasi berdasarkan kebijakan yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_1, 'name' => '1.4 Prioritas dalam program transformasi ditetapkan mengikuti rencana atau strategi organisasi.', 'description' => 'Menentukan prioritas program transformasi sesuai rencana strategis dan kebutuhan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_1, 'name' => '1.5 Otorisasi dan dukungan atas program transformasi didapatkan dari manajemen puncak.', 'description' => 'Memastikan persetujuan dan dukungan manajemen puncak terhadap pelaksanaan program transformasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_5,
            'title' => '2. Menetapkan strategi transformasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_5_2, 'name' => '2.1 Anggaran biaya dan sumber daya diidentifikasi untuk program transformasi.', 'description' => 'Mengidentifikasi kebutuhan anggaran dan sumber daya yang diperlukan untuk mendukung transformasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_2, 'name' => '2.2 Berbagai opsi dan solusi dari strategi dirumuskan secara kreatif dan terpadu.', 'description' => 'Merumuskan alternatif strategi dan solusi transformasi secara inovatif dan terintegrasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_2, 'name' => '2.3 Analisis manajemen risiko untuk transformasi dilakukan secara efektif dengan langkah mitigasi risiko.', 'description' => 'Melakukan identifikasi risiko transformasi serta menetapkan langkah mitigasi yang tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_2, 'name' => '2.4 Strategi transformasi dikembangkan bersama manajemen puncak dan pemangku kepentingan bila diperlukan.', 'description' => 'Mengembangkan strategi transformasi secara kolaboratif dengan pihak terkait untuk memastikan dukungan dan keberlanjutan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_5,
            'title' => '3. Menganalisis hambatan dalam proses transformasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_5_3, 'name' => '3.1 Anggaran biaya dialokasikan kepada sumber daya pelaksanaan strategi transformasi.', 'description' => 'Mengalokasikan anggaran secara tepat untuk mendukung pelaksanaan strategi transformasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_3, 'name' => '3.2 Kesenjangan kapabilitas sumber daya manusia diidentifikasi untuk mengurangi risiko transformasi.', 'description' => 'Mengidentifikasi kekurangan kompetensi SDM agar dapat disiapkan langkah pengembangan yang sesuai.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_5_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_5,
            'title' => '4. Memimpin pelaksanaan strategi transformasi penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_5_4, 'name' => '4.1 Anggota tim pelaksana transformasi dikonfirmasi atas peran dan tanggung jawabnya.', 'description' => 'Menetapkan dan memastikan kejelasan peran serta tanggung jawab setiap anggota tim transformasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_4, 'name' => '4.2 Pengembangan kapabilitas anggota tim diprioritaskan untuk mencapai strategi transformasi.', 'description' => 'Memprioritaskan peningkatan kompetensi tim agar mendukung keberhasilan transformasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_4, 'name' => '4.3 Kebijakan dan prosedur organisasi ditinjau untuk mendukung program transformasi.', 'description' => 'Meninjau dan menyesuaikan kebijakan serta prosedur agar selaras dengan kebutuhan transformasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_4, 'name' => '4.4 Proses dan pola komunikasi sasaran transformasi dikembangkan sesuai kebutuhan.', 'description' => 'Mengembangkan sistem komunikasi yang efektif untuk mendukung pemahaman dan pelaksanaan transformasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem5_5_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit5_5,
            'title' => '5. Mengevaluasi hasil dan strategi transformasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem5_5_5, 'name' => '5.1 Pencapaian sasaran dan hasil kuantitatif program transformasi dianalisis sesuai pelaksanaan strategi.', 'description' => 'Mengevaluasi hasil transformasi berdasarkan indikator kinerja dan target yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_5, 'name' => '5.2 Dampak terhadap kesinambungan prinsip sosial, ekonomi, dan lingkungan dievaluasi berdasarkan tolok ukur.', 'description' => 'Menilai dampak transformasi terhadap keberlanjutan sosial, ekonomi, dan lingkungan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem5_5_5, 'name' => '5.3 Peluang atas perubahan dan transformasi lanjutan dievaluasi.', 'description' => 'Mengevaluasi peluang pengembangan dan transformasi lanjutan untuk peningkatan kinerja berkelanjutan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 5: MOTIVATOR KINERJA TIM PENJUALAN');

        // ── Skema 6: PENDAMPING KEWIRAUSAHAAN ──
        $scheme6 = DB::table('schemes')->insertGetId([
            'code' => '09-010/SSO/MKC.SKM.006/XI/2023',
            'name' => 'PENDAMPING KEWIRAUSAHAAN',
            'type' => 'Okupasi',
            'description' => 'Skema Pendamping Kewirausahaan merupakan skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan dalam memberikan pendampingan kewirausahaan secara komprehensif kepada tenant atau pelaku usaha. Skema ini mencakup kompetensi dalam pendampingan pengurusan legalitas kelembagaan dan usaha, penyusunan profil perusahaan (company profile) dan rencana usaha (business plan), perhitungan biaya investasi, penentuan jenis produk, serta penentuan jumlah dan keahlian tenaga kerja yang dibutuhkan. Selain itu, skema ini juga menekankan kemampuan dalam menyusun dan mengelola strategi portofolio produk dan/atau merek, pendampingan pengurusan Hak Kekayaan Intelektual (HKI), serta penerapan komunikasi efektif dan pengembangan kerja sama tim dan individu. Sertifikasi ini ditujukan bagi tenaga pendamping kewirausahaan yang berperan dalam meningkatkan kesiapan, keberlanjutan, dan daya saing usaha sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 2000000,
            'recertification_fee' => 1500000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit6_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70IBT00.026.1',
            'unit_title' => 'Melakukan Pendampingan Pengurusan Legalitas Kelembagaan dan Usaha',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_1,
            'title' => '1. Menyiapkan bahan pendampingan pengurusan legalitas kelembagaan dan usaha',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_1_1, 'name' => '1.1 Produk barang dan jasa tenant diidentifikasi.', 'description' => 'Mengidentifikasi jenis produk barang dan/atau jasa yang dihasilkan tenant sebagai dasar penentuan kebutuhan legalitas usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_1_1, 'name' => '1.2 Kebutuhan legalitas kelembagaan dan usaha tenant diidentifikasi.', 'description' => 'Menentukan jenis legalitas yang diperlukan tenant sesuai bentuk usaha, skala, dan karakteristik kegiatan bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_1_1, 'name' => '1.3 Persyaratan, ketentuan dan/atau prosedur pengurusan legalitas kelembagaan dan usaha diidentifikasi.', 'description' => 'Mengidentifikasi persyaratan administrasi, ketentuan hukum, dan prosedur resmi pengurusan legalitas yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_1_1, 'name' => '1.4 Bahan pendampingan pengurusan legalitas kelembagaan dan usaha disiapkan sesuai prosedur.', 'description' => 'Menyiapkan materi, panduan, dan dokumen pendukung pendampingan sesuai prosedur dan ketentuan yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_1,
            'title' => '2. Melakukan pendampingan pengurusan legalitas kelembagaan dan usaha',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_1_2, 'name' => '2.1 Pelatihan atau konsultasi tentang persyaratan, ketentuan dan prosedur pengurusan legalitas kelembagaan dan usaha dilakukan.', 'description' => 'Memberikan pelatihan atau konsultasi kepada tenant terkait tata cara dan ketentuan pengurusan legalitas usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_1_2, 'name' => '2.2 Pendampingan penyiapan berkas persyaratan pengurusan legalitas kelembagaan dan usaha dilakukan.', 'description' => 'Mendampingi tenant dalam menyiapkan dokumen dan berkas persyaratan sesuai ketentuan instansi terkait.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_1_2, 'name' => '2.3 Pendampingan pengajuan berkas pendaftaran pengurusan legalitas kelembagaan dan usaha dilakukan.', 'description' => 'Mendampingi tenant dalam proses pengajuan dan pendaftaran legalitas kelembagaan dan usaha ke instansi berwenang.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_1_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_1,
            'title' => '3. Melakukan monitoring dan evaluasi hasil pengurusan legalitas kelembagaan dan usaha',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_1_3, 'name' => '3.1 Monitoring dan evaluasi pelaksanaan dan hasil pendampingan pengurusan legalitas kelembagaan dan usaha dilakukan.', 'description' => 'Melakukan pemantauan dan e', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70IBT00.048.1',
            'unit_title' => 'Melakukan Pendampingan Pembuatan Profil Perusahaan (Company Profile)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_2,
            'title' => '1. Menyiapkan bahan pendampingan pembuatan profil perusahaan (company profile)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_2_1, 'name' => '1.1 Karakteristik dan rencana usaha tenant diidentifikasi.', 'description' => 'Mengidentifikasi karakteristik bisnis, visi, misi, produk/jasa, serta rencana pengembangan usaha tenant sebagai dasar penyusunan company profile.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_2_1, 'name' => '1.2 Tim manajemen tenant (founder dan operasional) diidentifikasi.', 'description' => 'Mengidentifikasi struktur organisasi dan peran tim manajemen tenant untuk ditampilkan secara proporsional dalam profil perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_2_1, 'name' => '1.3 Kelembagaan dan legalitas usaha, sertifikasi dan merek produk tenant diidentifikasi.', 'description' => 'Mengidentifikasi status kelembagaan, legalitas usaha, sertifikasi, dan kepemilikan merek sebagai penguat kredibilitas perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_2_1, 'name' => '1.4 Bahan dan rencana pendampingan pembuatan profil perusahaan disusun sesuai prosedur.', 'description' => 'Menyusun bahan, jadwal, metode, dan rencana kerja pendampingan pembuatan company profile sesuai prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_2,
            'title' => '2. Melakukan pendampingan pembuatan profil perusahaan (company profile)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_2_2, 'name' => '2.1 Pendampingan penyiapan informasi dan data untuk pembuatan profil perusahaan (company profile) dilakukan.', 'description' => 'Mendampingi tenant dalam pengumpulan dan penyusunan data serta informasi yang akurat dan relevan untuk company profile.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_2_2, 'name' => '2.2 Pendampingan penyusunan draft profil perusahaan (company profile) dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun draft awal company profile sesuai struktur, tujuan, dan citra perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_2_2, 'name' => '2.3 Pendampingan finalisasi profil perusahaan (company profile) dilakukan.', 'description' => 'Mendampingi proses penyempurnaan dan finalisasi company profile agar siap digunakan sesuai kebutuhan promosi dan bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_2,
            'title' => '3. Melakukan pelaporan hasil pendampingan pembuatan profil perusahaan (company profile)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_2_3, 'name' => '3.1 Evaluasi hasil pelaksanaan pendampingan pembuatan profil perusahaan (company profile) dilakukan.', 'description' => 'Melakukan evaluasi terhadap proses dan hasil pendampingan pembuatan company profile berdasarkan tujuan yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_2_3, 'name' => '3.2 Laporan pelaksanaan dan hasil pendampingan pembuatan profil perusahaan (company profile) disusun untuk dilaporkan sesuai prosedur.', 'description' => 'Menyusun laporan kegiatan dan hasil pendampingan secara sistematis sesuai prosedur dan ketentuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70IBT00.025.1',
            'unit_title' => 'Melakukan Pendampingan Penyusunan Rencana Usaha (Business Plan) Tenant',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_3,
            'title' => '1. Menyiapkan perangkat pendampingan penyusunan rencana usaha tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_3_1, 'name' => '1.1 Profil perusahaan dan kinerja usaha tenant IBT diidentifikasi.', 'description' => 'Mengidentifikasi profil perusahaan, kondisi usaha, dan kinerja tenant IBT sebagai dasar pendampingan penyusunan rencana usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_1, 'name' => '1.2 Referensi tentang penyusunan rencana usaha disiapkan.', 'description' => 'Menyiapkan referensi yang mencakup model bisnis, analisis SWOT, teknik penyusunan rencana usaha, kertas kerja, program kerja, dan arus kas.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_1, 'name' => '1.3 Perangkat pendampingan penyusunan rencana usaha tenant IBT disiapkan sesuai prosedur.', 'description' => 'Menyiapkan modul, alat bantu, template, dan panduan pendampingan penyusunan rencana usaha sesuai prosedur yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_3,
            'title' => '2. Memberikan pendampingan penyusunan dan/atau validasi model bisnis tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_3_2, 'name' => '2.1 Pelatihan atau konsultasi tentang teknik pengembangan ide bisnis dan model bisnis tenant IBT dilakukan sesuai prosedur.', 'description' => 'Memberikan pelatihan atau konsultasi kepada tenant terkait pengembangan ide bisnis dan penyusunan model bisnis secara sistematis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_2, 'name' => '2.2 Coaching tentang penyusunan dan validasi model bisnis tenant IBT dilakukan sesuai prosedur.', 'description' => 'Melakukan coaching untuk menyusun, menguji, dan memvalidasi model bisnis tenant IBT agar sesuai dengan kondisi pasar dan tujuan usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_3_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_3,
            'title' => '3. Memberikan pendampingan penyusunan rencana usaha (business plan) tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_3_3, 'name' => '3.1 Pelatihan atau konsultasi tentang penyusunan rencana usaha (business plan) tenant IBT dilakukan.', 'description' => 'Memberikan pemahaman dan panduan teknis kepada tenant dalam menyusun rencana usaha yang komprehensif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.2 Coaching mengenai penyusunan visi dan misi usaha dilakukan.', 'description' => 'Mendampingi tenant dalam merumuskan visi dan misi usaha yang selaras dengan model bisnis dan peluang usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.3 Coaching mengenai penyusunan potensi pasar serta strategi promosi dan pemasaran dilakukan.', 'description' => 'Mendampingi tenant dalam menganalisis pasar sasaran serta menyusun strategi promosi dan pemasaran yang efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.4 Coaching mengenai analisis SWOT dan kelayakan bisnis dilakukan.', 'description' => 'Mendampingi tenant dalam melakukan analisis kekuatan, kelemahan, peluang, ancaman, serta kelayakan usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.5 Coaching mengenai penyusunan rencana pengembangan usaha 3–5 tahun ke depan dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun rencana pertumbuhan dan pengembangan usaha jangka menengah.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.6 Coaching mengenai penyusunan rencana organisasi, manajemen operasi dan produksi dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun struktur organisasi serta rencana operasional dan produksi usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.7 Coaching mengenai penyusunan rencana keuangan tenant IBT dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun rencana keuangan yang mencakup sumber pembiayaan, kebutuhan investasi, dan modal kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.8 Coaching mengenai analisis risiko usaha dan alternatif solusinya dilakukan.', 'description' => 'Mendampingi tenant dalam mengidentifikasi risiko usaha serta merumuskan alternatif solusi dan mitigasinya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_3, 'name' => '3.9 Coaching mengenai pemenuhan kecukupan isi dan format rencana usaha dilakukan.', 'description' => 'Memastikan rencana usaha tenant memenuhi kelengkapan isi dan format sesuai standar yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_3_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_3,
            'title' => '4. Memberikan pendampingan penyusunan program kerja tahunan dan arus kas usaha tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_3_4, 'name' => '4.1 Pelatihan atau konsultasi tentang penyusunan program kerja tahunan tenant IBT dilakukan sesuai prosedur.', 'description' => 'Memberikan pelatihan atau konsultasi mengenai penyusunan program kerja tahunan yang terstruktur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_4, 'name' => '4.2 Coaching mengenai penyusunan program kerja tahunan tenant IBT dilakukan sesuai prosedur.', 'description' => 'Mendampingi tenant dalam menyusun program kerja tahunan yang realistis dan terukur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_4, 'name' => '4.3 Coaching mengenai penyusunan proyeksi laba rugi dan arus kas (cash flow) tenant IBT dilakukan sesuai prosedur.', 'description' => 'Mendampingi tenant dalam menyusun proyeksi keuangan, laba rugi, dan arus kas usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_3_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_3,
            'title' => '5. Melakukan pelaporan pendampingan penyusunan rencana usaha tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_3_5, 'name' => '5.1 Monitoring dan evaluasi (monev) pelaksanaan dan hasil pendampingan dilakukan sesuai prosedur.', 'description' => 'Melakukan pemantauan dan evaluasi terhadap proses dan hasil pendampingan penyusunan rencana usaha tenant IBT.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_3_5, 'name' => '5.2 Laporan pelaksanaan dan hasil pendampingan disusun untuk dilaporkan.', 'description' => 'Menyusun laporan kegiatan dan hasil pendampingan secara sistematis sesuai ketentuan dan prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70IBT00.025.1',
            'unit_title' => 'Melakukan Pendampingan Penyusunan Rencana Usaha (Business Plan) Tenant',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_4,
            'title' => '1. Menyiapkan perangkat pendampingan penyusunan rencana usaha tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_4_1, 'name' => '1.1 Profil perusahaan dan kinerja usaha tenant IBT diidentifikasi.', 'description' => 'Mengidentifikasi profil perusahaan, kondisi usaha, dan kinerja tenant IBT sebagai dasar pendampingan penyusunan rencana usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_1, 'name' => '1.2 Referensi tentang penyusunan rencana usaha disiapkan.', 'description' => 'Menyiapkan referensi yang mencakup model bisnis, analisis SWOT, teknik penyusunan rencana usaha, kertas kerja, program kerja, dan arus kas.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_1, 'name' => '1.3 Perangkat pendampingan penyusunan rencana usaha tenant IBT disiapkan sesuai prosedur.', 'description' => 'Menyiapkan modul, alat bantu, template, dan panduan pendampingan penyusunan rencana usaha sesuai prosedur yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_4,
            'title' => '2. Memberikan pendampingan penyusunan dan/atau validasi model bisnis tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_4_2, 'name' => '2.1 Pelatihan atau konsultasi tentang teknik pengembangan ide bisnis dan model bisnis tenant IBT dilakukan sesuai prosedur.', 'description' => 'Memberikan pelatihan atau konsultasi kepada tenant terkait pengembangan ide bisnis dan penyusunan model bisnis secara sistematis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_2, 'name' => '2.2 Coaching tentang penyusunan dan validasi model bisnis tenant IBT dilakukan sesuai prosedur.', 'description' => 'Melakukan coaching untuk menyusun, menguji, dan memvalidasi model bisnis tenant IBT agar sesuai dengan kondisi pasar dan tujuan usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_4,
            'title' => '3. Memberikan pendampingan penyusunan rencana usaha (business plan) tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_4_3, 'name' => '3.1 Pelatihan atau konsultasi tentang penyusunan rencana usaha (business plan) tenant IBT dilakukan.', 'description' => 'Memberikan pemahaman dan panduan teknis kepada tenant dalam menyusun rencana usaha yang komprehensif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.2 Coaching mengenai penyusunan visi dan misi usaha dilakukan.', 'description' => 'Mendampingi tenant dalam merumuskan visi dan misi usaha yang selaras dengan model bisnis dan peluang usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.3 Coaching mengenai penyusunan potensi pasar serta strategi promosi dan pemasaran dilakukan.', 'description' => 'Mendampingi tenant dalam menganalisis pasar sasaran serta menyusun strategi promosi dan pemasaran yang efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.4 Coaching mengenai analisis SWOT dan kelayakan bisnis dilakukan.', 'description' => 'Mendampingi tenant dalam melakukan analisis kekuatan, kelemahan, peluang, ancaman, serta kelayakan usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.5 Coaching mengenai penyusunan rencana pengembangan usaha 3–5 tahun ke depan dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun rencana pertumbuhan dan pengembangan usaha jangka menengah.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.6 Coaching mengenai penyusunan rencana organisasi, manajemen operasi dan produksi dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun struktur organisasi serta rencana operasional dan produksi usaha.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.7 Coaching mengenai penyusunan rencana keuangan tenant IBT dilakukan.', 'description' => 'Mendampingi tenant dalam menyusun rencana keuangan yang mencakup sumber pembiayaan, kebutuhan investasi, dan modal kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.8 Coaching mengenai analisis risiko usaha dan alternatif solusinya dilakukan.', 'description' => 'Mendampingi tenant dalam mengidentifikasi risiko usaha serta merumuskan alternatif solusi dan mitigasinya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_3, 'name' => '3.9 Coaching mengenai pemenuhan kecukupan isi dan format rencana usaha dilakukan.', 'description' => 'Memastikan rencana usaha tenant memenuhi kelengkapan isi dan format sesuai standar yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_4_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_4,
            'title' => '4. Memberikan pendampingan penyusunan program kerja tahunan dan arus kas usaha tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_4_4, 'name' => '4.1 Pelatihan atau konsultasi tentang penyusunan program kerja tahunan tenant IBT dilakukan sesuai prosedur.', 'description' => 'Memberikan pelatihan atau konsultasi mengenai penyusunan program kerja tahunan yang terstruktur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_4, 'name' => '4.2 Coaching mengenai penyusunan program kerja tahunan tenant IBT dilakukan sesuai prosedur.', 'description' => 'Mendampingi tenant dalam menyusun program kerja tahunan yang realistis dan terukur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_4, 'name' => '4.3 Coaching mengenai penyusunan proyeksi laba rugi dan arus kas (cash flow) tenant IBT dilakukan sesuai prosedur.', 'description' => 'Mendampingi tenant dalam menyusun proyeksi keuangan, laba rugi, dan arus kas usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_4_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_4,
            'title' => '5. Melakukan pelaporan pendampingan penyusunan rencana usaha tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_4_5, 'name' => '5.1 Monitoring dan evaluasi (monev) pelaksanaan dan hasil pendampingan dilakukan sesuai prosedur.', 'description' => 'Melakukan pemantauan dan evaluasi terhadap proses dan hasil pendampingan penyusunan rencana usaha tenant IBT.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_4_5, 'name' => '5.2 Laporan pelaksanaan dan hasil pendampingan disusun untuk dilaporkan.', 'description' => 'Menyusun laporan kegiatan dan hasil pendampingan secara sistematis sesuai ketentuan dan prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.741000.006.01',
            'unit_title' => 'Menghitung biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_5,
            'title' => '1. Menyiapkan penghitungan biaya investasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_5_1, 'name' => '1.1 Lokasi produksi serta harga tanah dan atau bangunannya diidentifikasi', 'description' => 'Mengidentifikasi lokasi usaha serta mengetahui harga tanah dan/atau bangunan sebagai dasar perhitungan investasi awal.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_5_1, 'name' => '1.2 Produsen/supplier mesin dan peralatan, serta harga setiap mesin dan peralatan diidentifikasi', 'description' => 'Mengumpulkan data produsen atau supplier mesin dan peralatan beserta harga masing-masing sebagai komponen investasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_5_1, 'name' => '1.3 Kemudahan pengadaan mesin dan peralatan dari setiap produsen/supplier diidentifikasi', 'description' => 'Menilai tingkat kemudahan pengadaan mesin dan peralatan, termasuk waktu pengiriman dan ketersediaan suku cadang.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_5,
            'title' => '2. Menghitung biaya investasi untuk setiap jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_5_2, 'name' => '2.1 Lokasi produksi ditentukan berdasarkan Aturan Rencana Tata Ruang dan Wilayah yang berlaku', 'description' => 'Menentukan lokasi produksi yang sesuai dengan peraturan tata ruang dan ketentuan pemerintah setempat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_5_2, 'name' => '2.2 Harga tanah dan bangunan dihitung berdasarkan harga pasar', 'description' => 'Menghitung nilai investasi tanah dan bangunan berdasarkan harga pasar yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_5_2, 'name' => '2.3 Mesin dan peralatan yang akan digunakan untuk setiap produk diidentifikasi', 'description' => 'Menentukan jenis mesin dan peralatan yang dibutuhkan untuk memproduksi setiap jenis produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_5_2, 'name' => '2.4 Harga mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'description' => 'Menghitung biaya mesin dan peralatan berdasarkan daftar harga resmi atau penawaran dari distributor.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_5_2, 'name' => '2.5 Biaya instalasi mesin dan peralatan dihitung berdasarkan informasi dari distributor', 'description' => 'Menghitung biaya pemasangan, instalasi, dan uji coba mesin sebagai bagian dari biaya investasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.741000.006.01',
            'unit_title' => 'Menentukan Jenis Produk Yang Akan Diusahakan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_6,
            'title' => '1. Menyiapkan penentuan jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_6_1, 'name' => '1.1 Pengetahuan dasar tentang survey pasar dijelaskan.', 'description' => 'Menjelaskan konsep dasar survei pasar, tujuan survei, jenis data pasar, serta perannya dalam pengambilan keputusan penentuan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_6_1, 'name' => '1.2 Pengetahuan dasar tentang tata hitung ongkos dijelaskan.', 'description' => 'Menjelaskan prinsip perhitungan biaya produksi, biaya tetap dan variabel, serta kaitannya dengan penentuan harga produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_6_1, 'name' => '1.3 Pengetahuan dasar tentang pengolahan dan analisis data dijelaskan.', 'description' => 'Menjelaskan cara mengolah data survei dan data produksi serta melakukan analisis sederhana untuk mendukung penentuan jenis produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_6_1, 'name' => '1.4 Data hasil survey pasar, hasil studi proses produksi suatu produk, hasil perhitungan biaya investasi, hasil survey bahan baku, dan hasil analisis harga pokok disiapkan.', 'description' => 'Menyiapkan dan mengompilasi seluruh data pendukung yang dibutuhkan sebagai dasar analisis dalam menentukan jenis produk.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_6,
            'title' => '2. Menentukan jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_6_2, 'name' => '2.1 Pengelompokan setiap jenis produk dibuat berdasarkan hasil survey pasar, hasil studi proses produksi, hasil perhitungan biaya investasi, hasil survey bahan baku, dan hasil analisis harga pokok.', 'description' => 'Mengelompokkan produk berdasarkan karakteristik pasar, proses produksi, kebutuhan investasi, ketersediaan bahan baku, dan biaya produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_6_2, 'name' => '2.2 Hasil pengelompokan dianalisis.', 'description' => 'Menganalisis setiap kelompok produk untuk menilai potensi pasar, kelayakan produksi, dan tingkat keuntungan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_6_2, 'name' => '2.3 Jenis produk yang akan diusahakan ditentukan berdasarkan hasil analisis.', 'description' => 'Menetapkan jenis produk yang paling layak dan menguntungkan untuk diusahakan berdasarkan hasil analisis yang telah dilakukan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.741000.007.01',
            'unit_title' => 'Menentukan jumlah dan keahlian tenaga kerja yang dibutuhkan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_7,
            'title' => '1. Menyiapkan bagan proses produksi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_7_1, 'name' => '1.1 Tata cara penanganan bahan baku dan bahan pembantu untuk setiap jenis proses dijelaskan.', 'description' => 'Menjelaskan metode dan prosedur penanganan bahan baku serta bahan pembantu agar sesuai standar mutu, keamanan, dan efisiensi proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_1, 'name' => '1.2 Urutan proses produksi disiapkan.', 'description' => 'Menyusun tahapan proses produksi secara sistematis mulai dari bahan baku hingga produk akhir.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_1, 'name' => '1.3 Tata cara pengoperasian mesin dan peralatan dijelaskan.', 'description' => 'Menjelaskan prosedur penggunaan mesin dan peralatan produksi sesuai petunjuk teknis dan standar keselamatan kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_1, 'name' => '1.4 Tata cara penanganan produk antara dan produk akhir dijelaskan.', 'description' => 'Menjelaskan cara penanganan, penyimpanan sementara, dan pengamanan produk antara serta produk akhir agar kualitas tetap terjaga.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_7,
            'title' => '2. Menentukan kebutuhan tenaga kerja penanganan bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_7_2, 'name' => '2.1 Keahlian tenaga kerja yang menangani bahan baku dan bahan pembantu untuk setiap jenis proses dijelaskan.', 'description' => 'Menjelaskan kompetensi dan keterampilan tenaga kerja yang dibutuhkan sesuai karakteristik proses dan jenis bahan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_2, 'name' => '2.2 Waktu yang diperlukan untuk penanganan bahan baku dan bahan pembantu setiap volume tertentu untuk setiap jenis proses dihitung.', 'description' => 'Menghitung waktu kerja yang dibutuhkan berdasarkan volume produksi dan jenis proses untuk efisiensi tenaga kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_2, 'name' => '2.3 Jumlah dan keahlian tenaga kerja untuk menangani bahan baku dan bahan pembantu untuk setiap jenis proses ditentukan.', 'description' => 'Menentukan jumlah personel dan tingkat keahlian yang diperlukan agar proses produksi berjalan optimal.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_7_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_7,
            'title' => '3. Menentukan kebutuhan tenaga kerja penanganan produk antara dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_7_3, 'name' => '3.1 Keahlian tenaga kerja yang menangani produk antara dan produk akhir dijelaskan.', 'description' => 'Menjelaskan keterampilan dan kompetensi tenaga kerja dalam menangani produk antara dan produk akhir sesuai standar mutu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_3, 'name' => '3.2 Waktu yang diperlukan untuk penanganan produk antara dan produk akhir setiap volume tertentu dihitung.', 'description' => 'Menghitung kebutuhan waktu kerja berdasarkan volume produk untuk mendukung kelancaran alur produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_3, 'name' => '3.3 Jumlah dan keahlian tenaga kerja untuk menangani produk antara dan produk akhir ditentukan.', 'description' => 'Menentukan jumlah tenaga kerja dan keahliannya sesuai beban kerja dan target produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_7_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_7,
            'title' => '4. Menentukan kebutuhan tenaga kerja pengoperasian mesin dan peralatan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_7_4, 'name' => '4.1 Keahlian tenaga kerja yang mengoperasikan mesin dan peralatan dijelaskan.', 'description' => 'Menjelaskan kompetensi teknis yang diperlukan untuk mengoperasikan mesin dan peralatan produksi dengan aman dan efisien.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_4, 'name' => '4.2 Jumlah tenaga kerja yang mengoperasikan setiap mesin dan setiap peralatan dijelaskan berdasarkan buku panduan masing-masing mesin dan peralatan.', 'description' => 'Menentukan kebutuhan operator mesin sesuai spesifikasi dan ketentuan pada manual penggunaan mesin dan peralatan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_7_4, 'name' => '4.3 Jumlah tenaga kerja yang mengoperasikan mesin dan peralatan dihitung berdasarkan jumlah mesin dan peralatan yang ada.', 'description' => 'Menghitung total kebutuhan tenaga kerja operator berdasarkan jumlah mesin dan peralatan yang digunakan dalam proses produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_8 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70MKT00.018.2',
            'unit_title' => 'Menyusun dan Mengelola Strategi Portofolio Produk dan/atau Merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_8_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_8,
            'title' => '1. Menentukan tujuan portofolio produk dan/atau merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_8_1, 'name' => '1.1 Tujuan portofolio produk dan/atau merek ditetapkan berdasarkan strategi dan karakter merek organisasi', 'description' => 'Menjelaskan kemampuan dalam menetapkan tujuan portofolio produk/merek yang selaras dengan visi, misi, strategi bisnis, serta karakter dan nilai merek organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_8_1, 'name' => '1.2 Strategi portofolio produk dan/atau merek diidentifikasi berdasarkan tujuan portofolio produk dan/atau merek', 'description' => 'Menggambarkan kemampuan mengidentifikasi dan memilih strategi portofolio yang tepat untuk mencapai tujuan portofolio produk dan/atau merek yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_8_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_8,
            'title' => '2. Menetapkan keputusan dalam portofolio produk dan/atau merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_8_2, 'name' => '2.1 Ekuitas merek (brand equity) dan identitas merek diidentifikasi berdasarkan strategi portofolio', 'description' => 'Menunjukkan kemampuan menganalisis kekuatan merek dan identitas merek sebagai dasar pengambilan keputusan dalam pengelolaan portofolio.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_8_2, 'name' => '2.2 Audit portofolio produk dan/atau merek diidentifikasi berdasarkan tujuan pembuatan portofolio', 'description' => 'Menjelaskan proses evaluasi dan penilaian portofolio produk/merek untuk mengetahui kinerja, relevansi, dan kesesuaian dengan tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_8_2, 'name' => '2.3 Pengelolaan portofolio produk dan/atau merek ditetapkan sesuai strategi portofolio', 'description' => 'Menggambarkan kemampuan menetapkan keputusan pengelolaan portofolio (pengembangan, mempertahankan, atau menghentikan produk/merek) sesuai strategi yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_9 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70IBT00.032.1',
            'unit_title' => 'Melakukan Pendampingan Pengurusan Hak Kekayaan Intelektual (HKI)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_9_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_9,
            'title' => '1. Menyiapkan bahan pendampingan pengurusan HKI bagi tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_9_1, 'name' => '1.1 Jenis, persyaratan dan ketentuan pengurusan HKI bagi tenant IBT diidentifikasi', 'description' => 'Menjelaskan kemampuan mengidentifikasi jenis HKI yang relevan (merek, paten, hak cipta, desain industri, dll.) beserta persyaratan dan ketentuan pengurusannya sesuai regulasi yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_1, 'name' => '1.2 Kinerja dan karakteristik usaha dan produk tenant IBT diidentifikasi', 'description' => 'Menggambarkan kemampuan menganalisis kondisi usaha, kinerja bisnis, serta karakteristik produk tenant sebagai dasar penentuan kebutuhan HKI.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_1, 'name' => '1.3 Bahan pendampingan pengurusan HKI bagi tenant IBT disiapkan', 'description' => 'Menunjukkan kemampuan menyiapkan materi, dokumen, dan panduan pendampingan yang dibutuhkan dalam proses pengurusan HKI.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_9_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_9,
            'title' => '2. Melakukan pendampingan pengurusan HKI bagi tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_9_2, 'name' => '2.1 Konsultasi tentang pengurusan HKI bagi tenant IBT dilakukan', 'description' => 'Menjelaskan kemampuan memberikan konsultasi terkait jenis HKI, prosedur, manfaat, serta tahapan pengurusan HKI kepada tenant IBT.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_2, 'name' => '2.2 Kunjungan kerja dalam rangka pengamatan proses dan hasil produksi di tempat kerja bagi tenant IBT dilakukan sesuai prosedur', 'description' => 'Menggambarkan kemampuan melakukan observasi langsung terhadap proses dan hasil produksi sebagai bahan analisis kelayakan HKI.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_2, 'name' => '2.3 Analisis kesenjangan pemenuhan persyaratan dan ketentuan pengurusan HKI bagi tenant IBT dilakukan', 'description' => 'Menunjukkan kemampuan menganalisis perbedaan antara kondisi aktual tenant dengan persyaratan HKI yang harus dipenuhi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_2, 'name' => '2.4 Analisis kebutuhan dan kelayakan pengurusan HKI bagi tenant IBT dilakukan', 'description' => 'Menjelaskan kemampuan menilai kebutuhan dan kelayakan tenant dalam mengajukan HKI berdasarkan aspek teknis, hukum, dan bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_2, 'name' => '2.5 Pendampingan mengenai penyiapan pemenuhan persyaratan dan ketentuan, pemberkasan dan pengajuan pengurusan HKI bagi tenant IBT dilakukan', 'description' => 'Menggambarkan kemampuan mendampingi tenant dalam menyiapkan dokumen, mengisi formulir, dan mengajukan permohonan HKI sesuai prosedur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_2, 'name' => '2.6 Pendampingan pemenuhan kelengkapan berkas untuk pemenuhan persyaratan dan penerapan HKI dilakukan', 'description' => 'Menunjukkan kemampuan memastikan seluruh dokumen dan persyaratan HKI lengkap serta siap diterapkan oleh tenant.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_9_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_9,
            'title' => '3. Melakukan pelaporan pelaksanaan dan hasil pendampingan pengurusan HKI tenant IBT',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_9_3, 'name' => '3.1 Monitoring dan evaluasi (monev) pelaksanaan dan hasil pendampingan pengurusan HKI bagi tenant IBT dilakukan sesuai prosedur', 'description' => 'Menjelaskan kemampuan melakukan monitoring dan evaluasi terhadap proses dan hasil pendampingan HKI secara sistematis dan terukur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_9_3, 'name' => '3.2 Laporan pelaksanaan dan hasil pendampingan pengurusan HKI bagi tenant IBT disusun untuk dilaporkan sesuai prosedur', 'description' => 'Menggambarkan kemampuan menyusun laporan pendampingan HKI secara lengkap, jelas, dan sesuai format serta ketentuan pelaporan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_10 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'M.70MKT00.001.2',
            'unit_title' => 'Melaksanakan Komunikasi Efektif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_10_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_10,
            'title' => '1. Mengidentifikasi gaya komunikasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_10_1, 'name' => '1.1 Gaya komunikasi pelanggan diidentifikasi sesuai dengan karakter pelanggan.', 'description' => 'Mengamati dan mengenali tipe komunikasi pelanggan (asertif, pasif, agresif, atau pasif-agresif) agar pendekatan komunikasi dapat disesuaikan secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_10_1, 'name' => '1.2 Teknik menghadapi pelanggan dengan gaya komunikasi yang berbeda-beda diterapkan sesuai dengan kebutuhan pelanggan.', 'description' => 'Menerapkan teknik komunikasi yang sesuai untuk setiap tipe pelanggan agar interaksi berjalan efektif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_10_1, 'name' => '1.3 Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur organisasi.', 'description' => 'Menggunakan sarana komunikasi digital seperti telepon, email, dan aplikasi pesan sesuai standar dan aturan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_10_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_10,
            'title' => '2. Melaksanakan komunikasi langsung menggunakan bahasa lisan, tulisan, dan bahasa tubuh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_10_2, 'name' => '2.1 Pemilihan kata-kata saat berkomunikasi dilakukan secara cermat sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Memilih bahasa yang sopan, jelas, dan profesional agar pesan mudah dipahami dan tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_10_2, 'name' => '2.2 Bahasa tubuh, bahasa tulisan, dan ekspresi wajah dikendalikan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Mengatur sikap tubuh, ekspresi wajah, serta gaya penulisan untuk mendukung pesan komunikasi secara positif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_10_2, 'name' => '2.3 Tata penampilan profesional dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Menjaga kerapihan dan kesesuaian penampilan agar mencerminkan sikap profesional dan meningkatkan kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_10_2, 'name' => '2.4 Komunikasi antara pelanggan dan staf dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Melaksanakan komunikasi dua arah yang sopan, terbuka, dan saling menghargai sesuai norma komunikasi bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit6_11 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme6,
            'unit_code' => 'N.821100.052.01',
            'unit_title' => 'Mengembangkan Kerjasama Tim dan Individu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem6_11_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_11,
            'title' => '1. Membangun kerjasama (team work)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_11_1, 'name' => '1.1 Keinginan untuk bekerjasama sebagai anggota tim ditunjukkan', 'description' => 'Menunjukkan sikap positif, keterbukaan, dan kemauan untuk berkontribusi aktif sebagai bagian dari tim dalam mencapai tujuan bersama.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_1, 'name' => '1.2 Kegiatan sesuai ruang lingkup dilakukan', 'description' => 'Melaksanakan tugas dan aktivitas kerja sesuai peran, fungsi, dan batasan kewenangan yang telah ditetapkan dalam tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_1, 'name' => '1.3 Rasa hormat dan empati ditunjukkan', 'description' => 'Menunjukkan sikap saling menghargai, memahami perbedaan, serta memiliki empati terhadap anggota tim lain dalam bekerja sama.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_11_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_11,
            'title' => '2. Mengembangkan komitmen tim dan kerjasama',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_11_2, 'name' => '2.1 Kelompok, perorangan dan organisasi yang relevan diidentifikasi', 'description' => 'Mengidentifikasi pihak internal maupun eksternal yang memiliki peran dan kepentingan dalam mendukung kerja sama tim dan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_2, 'name' => '2.2 Hubungan interpersonal dilakukan untuk membangun kerjasama yang memungkinkan', 'description' => 'Membangun komunikasi dan hubungan interpersonal yang efektif guna menciptakan kerja sama yang saling menguntungkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_2, 'name' => '2.3 Jejaring yang telah dibangun ditingkatkan guna mempertahankan hubungan bisnis', 'description' => 'Mengembangkan dan memelihara jaringan kerja sama yang sudah ada untuk menjaga keberlanjutan hubungan kerja dan bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_2, 'name' => '2.4 Rencana dan tujuan dikembangkan untuk kemudian dikomunikasikan', 'description' => 'Menyusun rencana kerja dan tujuan tim secara jelas serta mengkomunikasikannya kepada seluruh pihak terkait.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_2, 'name' => '2.5 Peranan dan tanggung jawab anggota tim dikomunikasikan', 'description' => 'Menjelaskan dan menyampaikan peran serta tanggung jawab masing-masing anggota tim agar tidak terjadi tumpang tindih tugas.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_2, 'name' => '2.6 Gaya komunikasi yang mendukung dilakukan untuk meningkatkan motivasi', 'description' => 'Menerapkan gaya komunikasi yang positif, persuasif, dan suportif untuk meningkatkan semangat dan motivasi kerja tim.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem6_11_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit6_11,
            'title' => '3. Mengelola kinerja tim',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem6_11_3, 'name' => '3.1 Kinerja tim dinilai dan dikembangkan sesuai kebutuhan', 'description' => 'Menilai pencapaian kinerja tim dan merancang upaya pengembangan untuk meningkatkan efektivitas dan produktivitas tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_3, 'name' => '3.2 Kegiatan kemajuan kinerja tim dimonitor', 'description' => 'Memantau secara berkala perkembangan dan kemajuan kinerja tim berdasarkan rencana dan target yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_3, 'name' => '3.3 Tugas-tugas dan pertanggungjawaban didelegasikan dan dimonitor secara memadai', 'description' => 'Mendelegasikan tugas sesuai kompetensi anggota tim serta memastikan pelaksanaannya berjalan sesuai rencana.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_3, 'name' => '3.4 Dukungan dan bimbingan diberikan', 'description' => 'Memberikan arahan, dukungan, dan bimbingan kepada anggota tim untuk mengatasi kendala dan meningkatkan kinerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem6_11_3, 'name' => '3.5 Upaya pencapaian kinerja tim diakui dan dihargai', 'description' => 'Mengakui serta memberikan apresiasi atas kontribusi dan pencapaian kinerja tim sebagai bentuk motivasi dan penghargaan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 6: PENDAMPING KEWIRAUSAHAAN');

        // ── Skema 7: PETUGAS PROMOSI PENJUALAN ──
        $scheme7 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.007/XI/2023',
            'name' => 'PETUGAS PROMOSI PENJUALAN',
            'type' => 'Okupasi',
            'description' => 'Skema Petugas Promosi Penjualan merupakan skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan dalam melaksanakan kegiatan promosi dan penjualan produk barang dan/atau jasa secara efektif dan berorientasi pada pencapaian target. Skema ini mencakup kompetensi dalam pengembangan pengetahuan produk, penerapan keterampilan penjualan, penyusunan rencana aktivitas penjualan, serta pelaksanaan kegiatan promosi merek. Selain itu, skema ini menekankan kemampuan berpikir kritis dan kreatif dalam menghadapi dinamika pasar, serta kemampuan menangani keluhan pelanggan secara profesional untuk menjaga kepuasan dan loyalitas pelanggan. Sertifikasi ini ditujukan bagi petugas promosi penjualan yang berperan dalam meningkatkan kinerja penjualan dan citra merek organisasi sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 1250000,
            'recertification_fee' => 1500000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit7_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.032.1',
            'unit_title' => 'Mencapai Target Penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_1,
            'title' => '1. Menentukan target penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_1_1, 'name' => '1.1 Target penjualan tim dengan personel yang relevan diidentifikasi dari rencana penjualan', 'description' => 'Mengidentifikasi target penjualan tim berdasarkan rencana penjualan organisasi serta menyesuaikannya dengan jumlah dan kapasitas personel penjualan yang terlibat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_1, 'name' => '1.2 Target penjualan pribadi dalam penjualan dianalisis sesuai dengan strategi organisasi', 'description' => 'Menganalisis target penjualan individu agar selaras dengan strategi, sasaran, dan kebijakan penjualan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_1, 'name' => '1.3 Secara teratur kemajuan menuju target penjualan dipantau secara berkala', 'description' => 'Melakukan pemantauan rutin terhadap realisasi penjualan untuk memastikan pencapaian target sesuai rencana.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_1, 'name' => '1.4 Faktor umum yang mendukung atau menghalangi penjualan dianalisis oleh manajemen organisasi', 'description' => 'Mengidentifikasi dan menganalisis faktor internal dan eksternal yang dapat memengaruhi keberhasilan atau kegagalan pencapaian target penjualan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_1,
            'title' => '2. Menentukan faktor-faktor yang mempengaruhi pencapaian target penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_1_2, 'name' => '2.1 Faktor-faktor yang memengaruhi kinerja penjualan terhadap target penjualan dievaluasi bersama dengan manajemen organisasi', 'description' => 'Mengevaluasi kinerja penjualan dan faktor pendukung maupun penghambat pencapaian target secara bersama dengan pihak manajemen.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_2, 'name' => '2.2 Kendala-kendala yang akan memengaruhi pencapaian target penjualan dianalisis bersama dengan manajemen organisasi', 'description' => 'Menganalisis berbagai kendala operasional, pasar, maupun sumber daya yang berpotensi menghambat pencapaian target penjualan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_2, 'name' => '2.3 Target penjualan baru dibuat berdasarkan evaluasi pencapaian', 'description' => 'Menyusun dan menetapkan target penjualan yang baru berdasarkan hasil evaluasi pencapaian dan kondisi terkini organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_1_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_1,
            'title' => '3. Mencapai target penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_1_3, 'name' => '3.1 Hasil penjualan dari tenaga penjualan yang kurang berkinerja dengan pelanggan dianalisis untuk perbaikan kemajuan', 'description' => 'Menganalisis hasil penjualan tenaga penjual yang kinerjanya belum optimal untuk menentukan langkah perbaikan dan peningkatan kinerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_3, 'name' => '3.2 Kemungkinan berbagai alternatif peluang penjualan ditingkatkan sesuai dengan kondisi terkini', 'description' => 'Mengidentifikasi dan memanfaatkan peluang penjualan alternatif yang relevan dengan situasi pasar dan kondisi organisasi saat ini.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_1_3, 'name' => '3.3 Kemajuan penjualan dari tim penjualan dilaporkan kepada atasan yang relevan', 'description' => 'Menyusun dan menyampaikan laporan perkembangan penjualan tim secara berkala kepada atasan atau manajemen terkait.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit7_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.033.1',
            'unit_title' => 'Mengembangkan Pengetahuan Produk (Barang/Jasa)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_2,
            'title' => '1. Mencari informasi dan pengetahuan tentang produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_2_1, 'name' => '1.1 Sumber informasi tentang produk diidentifikasi sesuai prosedur organisasi.', 'description' => 'Menentukan dan menggunakan sumber informasi resmi dan terpercaya sesuai kebijakan organisasi untuk memperoleh data produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_1, 'name' => '1.2 Fitur utama, tujuan, dan penggunaan produk diidentifikasi dari panduan spesifikasi produk.', 'description' => 'Mengkaji dokumen spesifikasi produk untuk memahami fungsi, tujuan, dan cara penggunaan produk secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_1, 'name' => '1.3 Kekuatan dan kelemahan produk diidentifikasi dari informasi yang relevan.', 'description' => 'Menganalisis keunggulan dan keterbatasan produk berdasarkan data teknis, pengalaman pengguna, dan sumber lain yang relevan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_1, 'name' => '1.4 Jaminan dan dukungan layanan diidentifikasi secara detail.', 'description' => 'Memahami ketentuan garansi, layanan purna jual, serta bentuk dukungan teknis yang diberikan kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_2,
            'title' => '2. Menerjemahkan pengetahuan produk menjadi manfaat',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_2_2, 'name' => '2.1 Daya tarik dari fitur produk diinterpretasikan sesuai kebutuhan pelanggan.', 'description' => 'Menghubungkan fitur produk dengan kebutuhan dan permasalahan pelanggan agar nilai produk mudah dipahami.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_2, 'name' => '2.2 Manfaat dan fitur produk yang kritikal dijelaskan sesuai prosedur organisasi.', 'description' => 'Menjelaskan fitur utama dan manfaat penting produk dengan bahasa yang jelas dan sesuai standar organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_2, 'name' => '2.3 Manfaat produk bagi pembeli dipresentasikan sesuai prosedur organisasi.', 'description' => 'Menyampaikan manfaat produk kepada calon pembeli melalui presentasi atau penjelasan yang terstruktur dan persuasif.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_2,
            'title' => '3. Mengevaluasi produk pesaing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_2_3, 'name' => '3.1 Sumber informasi produk pesaing diidentifikasi dari berbagai sumber yang relevan.', 'description' => 'Mengumpulkan data produk pesaing dari sumber internal, eksternal, dan publik yang dapat dipertanggungjawabkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_3, 'name' => '3.2 Fitur, keunggulan, kekuatan, dan kelemahan produk dibandingkan dengan produk pesaing.', 'description' => 'Melakukan analisis perbandingan produk organisasi dengan produk pesaing untuk mengetahui posisi kompetitif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_2_3, 'name' => '3.3 Kelebihan produk organisasi dibandingkan dengan produk pesaing dikomunikasikan kepada pembeli.', 'description' => 'Mengomunikasikan nilai tambah dan keunggulan produk organisasi secara objektif dan meyakinkan kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit7_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.036.2',
            'unit_title' => 'Melaksanakan Keterampilan Penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_3,
            'title' => '1. Membuka percakapan dengan calon pelanggan potensial',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_3_1, 'name' => '1.1 Nama, jabatan, serta organisasi tempat bekerja diperkenalkan pada awal percakapan', 'description' => 'Memulai percakapan dengan memperkenalkan diri, jabatan, dan organisasi untuk membangun kredibilitas dan hubungan awal.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_3_1, 'name' => '1.2 Tujuan pertemuan penjualan disampaikan dengan jelas', 'description' => 'Menyampaikan maksud dan tujuan pertemuan agar calon pelanggan memahami agenda dan konteks pertemuan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_3_1, 'name' => '1.3 Percakapan pembuka dilakukan agar suasana kondusif tercipta', 'description' => 'Menggunakan teknik komunikasi yang tepat untuk menciptakan suasana nyaman dan terbuka sehingga calon pelanggan siap berdiskusi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_3,
            'title' => '2. Melaksanakan penggalian (probing) kebutuhan terhadap calon pelanggan potensial',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_3_2, 'name' => '2.1 Teknik identifikasi kebutuhan pelanggan dilakukan dengan pertanyaan seputar situasi, masalah, dan dampak dari masalah', 'description' => 'Menggali informasi mendalam tentang kondisi, tantangan, dan kebutuhan pelanggan untuk memahami masalah yang ingin diselesaikan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_3_2, 'name' => '2.2 Solusi penjualan disampaikan sesuai kebutuhan pelanggan', 'description' => 'Menyampaikan produk atau jasa yang relevan dengan kebutuhan dan masalah yang diidentifikasi dari calon pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_3_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_3,
            'title' => '3. Melaksanakan presentasi penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_3_3, 'name' => '3.1 Tipe dan jenis presentasi yang akan disampaikan ditentukan sesuai dengan kebutuhan pelanggan', 'description' => 'Menentukan format presentasi (demo, visual, digital, dll.) yang paling sesuai dengan preferensi dan kebutuhan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_3_3, 'name' => '3.2 Berbagai perlengkapan dalam penjelasan produk disiapkan sesuai dengan kebutuhan pelanggan', 'description' => 'Menyiapkan materi presentasi, brosur, sampel, atau alat bantu lain yang relevan untuk mendukung pemahaman calon pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_3_3, 'name' => '3.3 Pesan utama disampaikan sesuai tujuan presentasi', 'description' => 'Menekankan informasi inti dan nilai produk/jasa yang ingin disampaikan agar pesan dapat dipahami dan diingat calon pelanggan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_3_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_3,
            'title' => '4. Melaksanakan penanganan terhadap keberatan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_3_4, 'name' => '4.1 Feature, Advantage, Benefit (FAB) dari produk/jasa disebutkan sesuai dengan kebutuhan pelanggan', 'description' => 'Menjelaskan fitur, keunggulan, dan manfaat produk/jasa yang relevan dengan kebutuhan dan masalah pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_3_4, 'name' => '4.2 Hal yang menjadi keberatan pelanggan dijawab dengan menyebutkan nilai (value) yang terdapat pada produk/jasa yang ditawarkan', 'description' => 'Menangani keberatan pelanggan dengan menyampaikan nilai tambah produk/jasa sehingga menurunkan resistensi dan meningkatkan peluang closing.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit7_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.040.1',
            'unit_title' => 'Mengembangkan Cara Berfikir Kritis dan Kreatif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_4,
            'title' => '1. Mengembangkan pola pikir kritis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_4_1, 'name' => '1.1 Kebiasaan mengajukan pertanyaan dikembangkan dari perspektif yang berbeda', 'description' => 'Mengajukan pertanyaan yang beragam untuk menilai masalah dari berbagai sudut pandang dan memperluas pemahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_1, 'name' => '1.2 Berbagai sumber informasi yang kredibel dan relevan dikembangkan berdasarkan kebutuhan organisasi', 'description' => 'Mengumpulkan informasi yang dapat dipercaya dari berbagai sumber untuk mendukung analisis dan keputusan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_1, 'name' => '1.3 Pertanyaan utama atas masalah dan tantangan disusun sesuai dengan informasi dan ide terkumpul', 'description' => 'Menyusun pertanyaan penting yang menyoroti inti masalah berdasarkan data dan ide yang telah dikumpulkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_1, 'name' => '1.4 Kendala aktual dalam perumusan masalah ditentukan sesuai dengan kondisi terkini', 'description' => 'Mengidentifikasi hambatan nyata yang mempengaruhi perumusan masalah agar solusi lebih tepat sasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_4,
            'title' => '2. Menghasilkan ide dan tanggapan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_4_2, 'name' => '2.1 Berbagai teknik berpikir kreatif digunakan untuk menghasilkan ide dan tanggapan', 'description' => 'Memanfaatkan metode seperti brainstorming, mind mapping, atau analisis SWOT untuk menghasilkan ide baru.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.2 Ide-ide terkait dengan tujuan diperiksa keabsahannya berdasarkan data yang ada', 'description' => 'Mengevaluasi ide dengan membandingkan fakta dan data untuk memastikan relevansi dan kepraktisan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.3 Faktor penghambat untuk pemikiran kreatif diidentifikasi bersama dengan manajemen', 'description' => 'Menentukan hambatan internal maupun eksternal yang dapat menghalangi pengembangan ide kreatif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.4 Realita di luar situasi terkait dipertimbangkan sesuai dengan kebutuhan organisasi', 'description' => 'Mempertimbangkan kondisi eksternal yang memengaruhi penerapan ide agar keputusan lebih realistis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.5 Batasan dan kendala yang ada dievaluasi bersama dengan manajemen', 'description' => 'Mengevaluasi keterbatasan sumber daya, waktu, atau kebijakan yang dapat mempengaruhi ide.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.6 Kemauan mengambil risiko atas ide dan buah pikiran ditunjukkan untuk mendukung strategi organisasi', 'description' => 'Menunjukkan keberanian untuk mencoba ide baru yang mendukung tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.7 Pengamatan, pengalaman, ide dan hasil refleksi diri didokumentasikan sesuai dengan prosedur organisasi', 'description' => 'Mencatat proses berpikir dan refleksi sebagai dokumentasi untuk evaluasi dan pengembangan selanjutnya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.8 Pemikiran kontingensi dan intuisi dari pengalaman dipertimbangkan sesuai dengan topik pembahasan', 'description' => 'Menggunakan intuisi dan pengalaman sebelumnya untuk memperkuat keputusan dan ide.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_2, 'name' => '2.9 Hubungan dan pemikiran atas hal-hal yang tidak relevan dikesampingkan berdasarkan topik pembahasan', 'description' => 'Mengeliminasi informasi yang tidak relevan agar fokus pada solusi dan tujuan utama.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_4,
            'title' => '3. Menguji dan menciptakan kembali ide-ide',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_4_3, 'name' => '3.1 Asumsi di balik ide diidentifikasi bersama dengan manajemen', 'description' => 'Mengevaluasi dasar pemikiran dari ide untuk memastikan logika dan relevansi dengan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_3, 'name' => '3.2 Variasi solusi dan ide yang berbeda diidentifikasi bersama dengan manajemen', 'description' => 'Membandingkan beberapa alternatif solusi untuk menentukan yang paling efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_3, 'name' => '3.3 Keabsahan, kehandalan dan kemungkinan penerapan ide dipertanyakan dengan cara yang tepat', 'description' => 'Mengkritisi setiap ide agar dapat diterapkan secara praktis dan efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_3, 'name' => '3.4 Pendapat dari orang lain dipertimbangkan sesuai dengan topik pembahasan', 'description' => 'Mengakomodasi perspektif pihak lain untuk memperkaya kualitas ide.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_3, 'name' => '3.5 Ide disampaikan dengan situasi dan sudut pandang yang berbeda', 'description' => 'Menyajikan ide secara fleksibel agar mudah dipahami oleh berbagai pihak.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_4_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_4,
            'title' => '4. Mengembangkan keahlian berpikir kreatif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_4_4, 'name' => '4.1 Pola pikir dan bentuk tanggapan diidentifikasi terhadap situasi kerja dan kehidupan tertentu', 'description' => 'Mengenali cara berpikir dan tanggapan yang sesuai konteks untuk pengambilan keputusan kreatif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_4, 'name' => '4.2 Pengukuran efektivitas ide baru, cara berpikir rasional dan out-of-the-box dikembangkan sesuai dengan kebutuhan organisasi', 'description' => 'Mengembangkan metode evaluasi ide kreatif untuk memastikan dampak positif terhadap organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_4, 'name' => '4.3 Kesempatan berlatih dalam menguji teknik berpikir kreatif dilakukan pada berbagai situasi kerja dan kehidupan sehari-hari', 'description' => 'Memberikan pengalaman langsung dalam mengaplikasikan ide kreatif di berbagai kondisi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_4_4, 'name' => '4.4 Teknik berbicara proaktif dikembangkan untuk berbagi cara dalam berpikir kreatif kepada lingkungan kerja', 'description' => 'Melatih komunikasi efektif untuk menyampaikan ide kreatif dan mempengaruhi tim secara positif.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit7_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.017.1',
            'unit_title' => 'Melaksanakan Kegiatan Promosi Merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_5,
            'title' => '1. Meneliti informasi pemasaran',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_5_1, 'name' => '1.1 Konsep pemasaran yang akan dilakukan diteliti kesesuaiannya dengan strategi organisasi', 'description' => 'Memastikan konsep pemasaran sejalan dengan visi, misi, dan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_1, 'name' => '1.2 Rencana pemasaran dan kebijakan/prosedur yang relevan diidentifikasi dan dianalisis kesesuaiannya dengan strategi organisasi', 'description' => 'Mengevaluasi rencana pemasaran dan prosedur agar mendukung strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_1, 'name' => '1.3 Kebutuhan pemasaran diidentifikasi berdasarkan rencana penjualan', 'description' => 'Menentukan jenis informasi dan data pemasaran yang dibutuhkan untuk mendukung target penjualan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_1, 'name' => '1.4 Aktivitas pemasaran sebelumnya diselidiki untuk mencari informasi kesuksesannya', 'description' => 'Menganalisis data historis untuk mengetahui keberhasilan strategi pemasaran sebelumnya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_1, 'name' => '1.5 Profil segmen pasar diidentifikasi kesesuaiannya dengan strategi organisasi', 'description' => 'Menyusun karakteristik segmen pasar agar selaras dengan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_1, 'name' => '1.6 Posisi dan bauran pemasaran di tiap segmen pasar diidentifikasi kesesuaiannya dengan strategi organisasi', 'description' => 'Menilai keefektifan elemen produk, harga, promosi, dan distribusi di setiap segmen.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_1, 'name' => '1.7 Hasil yang diharapkan dari pemasaran diidentifikasi kesesuaiannya dengan strategi organisasi', 'description' => 'Menetapkan target dan outcome pemasaran yang sejalan dengan strategi perusahaan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_5,
            'title' => '2. Merencanakan kegiatan komunikasi merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_5_2, 'name' => '2.1 Kumpulan informasi pemasaran dianalisis berdasarkan strategi organisasi', 'description' => 'Mengolah data pemasaran untuk merancang aktivitas komunikasi merek yang efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_2, 'name' => '2.2 Rencana aktivitas komunikasi merek dikumpulkan dan dikembangkan kesesuaiannya dengan kondisi terkini', 'description' => 'Menyusun rencana komunikasi merek yang relevan dengan situasi pasar dan kebutuhan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_2, 'name' => '2.3 Persetujuan terhadap rencana komunikasi merek didapatkan dari atasan', 'description' => 'Mendapatkan otorisasi untuk implementasi kegiatan komunikasi merek.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_5,
            'title' => '3. Melakukan kegiatan komunikasi merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_5_3, 'name' => '3.1 Tipe komunikasi merek yang diperlukan ditentukan berdasarkan strategi organisasi', 'description' => 'Memilih metode komunikasi yang sesuai untuk meningkatkan kesadaran dan citra merek.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_3, 'name' => '3.2 Aktivitas komunikasi merek dilakukan sesuai peran', 'description' => 'Melaksanakan aktivitas komunikasi sesuai tanggung jawab dan strategi yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_3, 'name' => '3.3 Tugas dan fungsi pemasaran dipasangkan dengan pihak yang terlibat', 'description' => 'Menentukan siapa yang bertanggung jawab atas setiap aktivitas pemasaran untuk efektivitas implementasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_3, 'name' => '3.4 Kegiatan pemasaran dipantau dan rencana kegiatan diulas oleh tim internal', 'description' => 'Memantau progres dan mengevaluasi aktivitas untuk memastikan pencapaian tujuan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_5_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_5,
            'title' => '4. Mengulas kegiatan penjualan sehubungan kegiatan promosi merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_5_4, 'name' => '4.1 Hasil dari kegiatan penjualan direkam dan diukur sesuai prosedur organisasi', 'description' => 'Mencatat dan menilai hasil penjualan agar dapat dianalisis untuk pengambilan keputusan berikutnya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_4, 'name' => '4.2 Kegiatan penjualan diulas berdasarkan harapan dan hasil yang tercapai', 'description' => 'Membandingkan target dengan realisasi untuk mengevaluasi efektivitas promosi merek.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_5_4, 'name' => '4.3 Laporan penjualan disiapkan dan dikomunikasikan dengan pihak yang terlibat', 'description' => 'Menyusun laporan penjualan untuk komunikasi internal dan pengambilan keputusan strategi pemasaran selanjutnya.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit7_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.020.2',
            'unit_title' => 'Menangani Keluhan Pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_6,
            'title' => '1. Menerima keluhan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_6_1, 'name' => '1.1 Keluhan pelanggan didengarkan sepenuhnya, tanpa memotong', 'description' => 'Mendengarkan secara aktif semua keluhan pelanggan untuk memahami permasalahan secara menyeluruh.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_1, 'name' => '1.2 Keluhan pelanggan dikonfirmasi ulang kepada pelanggan', 'description' => 'Memastikan keluhan yang diterima sesuai dengan pemahaman pihak organisasi sebelum tindakan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_1, 'name' => '1.3 Permohonan maaf atas ketidaknyamanan yang dialami disampaikan secara sopan', 'description' => 'Menunjukkan empati dan sikap profesional kepada pelanggan yang mengalami masalah.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_1, 'name' => '1.4 Langkah-langkah yang akan diambil untuk menanggapi keluhan dijelaskan secara berurutan', 'description' => 'Menjelaskan prosedur penanganan keluhan secara jelas dan sistematis kepada pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_1, 'name' => '1.5 Tenggat waktu penanganan keluhan disampaikan kepada pelanggan', 'description' => 'Memberikan informasi kapan keluhan akan ditangani untuk mengelola ekspektasi pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_1, 'name' => '1.6 Catatan tertulis terhadap keseluruhan keluhan pelanggan dibuat sesuai prosedur organisasi', 'description' => 'Mencatat seluruh keluhan untuk keperluan tindak lanjut dan dokumentasi resmi organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_6,
            'title' => '2. Mempersiapkan penanganan keluhan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_6_2, 'name' => '2.1 Keseluruhan rincian keluhan pelanggan dikomunikasikan kepada seluruh pihak internal yang terlibat', 'description' => 'Menyebarkan informasi keluhan ke tim terkait agar penanganan lebih terkoordinasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_2, 'name' => '2.2 Sebab utama keluhan pelanggan diidentifikasi berdasarkan informasi yang disampaikan pelanggan', 'description' => 'Menentukan akar masalah untuk mempermudah solusi yang tepat dan efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_2, 'name' => '2.3 Kondisi spesifik pelanggan yang mempengaruhi penanganan diidentifikasi sesuai dengan informasi relevan yang dimiliki organisasi', 'description' => 'Memahami konteks dan kebutuhan pelanggan agar solusi sesuai dengan situasi spesifik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_2, 'name' => '2.4 Skala prioritas penanganan ditentukan berdasarkan jenis keluhan maupun tipe pelanggan', 'description' => 'Menetapkan urutan penanganan berdasarkan urgensi dan dampak keluhan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_2, 'name' => '2.5 Rencana penanganan keluhan pelanggan disusun sesuai dengan prosedur organisasi', 'description' => 'Menyusun langkah-langkah solusi dan penugasan tim sesuai SOP organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_6_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_6,
            'title' => '3. Melaksanakan penanganan keluhan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_6_3, 'name' => '3.1 Permohonan maaf kepada pelanggan disampaikan mengikuti prosedur operasional organisasi', 'description' => 'Menunjukkan kesopanan dan profesionalisme saat menghadapi pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_3, 'name' => '3.2 Penyebab utama terjadinya keluhan pelanggan dijelaskan secara terstruktur', 'description' => 'Memberikan klarifikasi atas masalah yang terjadi untuk membangun kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_3, 'name' => '3.3 Solusi terhadap keluhan pelanggan ditawarkan berdasarkan kebijakan organisasi', 'description' => 'Menawarkan tindakan korektif yang sesuai prosedur dan kebijakan perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_3, 'name' => '3.4 Kesediaan pelanggan menerima solusi dikonfirmasikan ulang kepada pelanggan', 'description' => 'Memastikan pelanggan memahami dan menyetujui solusi yang diberikan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_3, 'name' => '3.5 Alternatif solusi penanganan keluhan pelanggan ditawarkan sesuai kebijakan organisasi', 'description' => 'Memberikan opsi solusi jika solusi utama tidak dapat diterima pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_3, 'name' => '3.6 Ucapan terima kasih disampaikan kepada pelanggan atas kesediaan menerima alternatif solusi', 'description' => 'Menunjukkan apresiasi atas kerja sama dan kesabaran pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_6_3, 'name' => '3.7 Informasi mengenai standar layanan prima organisasi diberikan secara lengkap', 'description' => 'Memberikan edukasi kepada pelanggan tentang kualitas layanan dan prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit7_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme7,
            'unit_code' => 'M.70MKT00.031.2',
            'unit_title' => 'Menyusun Rencana Aktifitas Penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem7_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_7,
            'title' => '1. Merencanakan aktivitas penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_7_1, 'name' => '1.1 Aktivitas penjualan untuk pelanggan yang ada dan potensial direncanakan sesuai dengan rencana pemasaran atau sistem organisasi lainnya', 'description' => 'Menyusun rencana penjualan yang sesuai dengan strategi organisasi, target individu dan tim, serta kebutuhan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_1, 'name' => '1.2 Proses perencanaan penjualan disusun berdasarkan informasi bisnis, pelanggan dan pasar yang valid dan handal', 'description' => 'Mengumpulkan dan menganalisis data pasar dan pelanggan untuk dasar perencanaan yang akurat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_1, 'name' => '1.3 Prospek penjualan diidentifikasi dari profil pelanggan', 'description' => 'Menentukan calon pelanggan potensial berdasarkan informasi profil dan riwayat pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_1, 'name' => '1.4 Peluang penjualan yang inovatif dicari dan dievaluasi secara proaktif, sedangkan teknologi dan media baru dimanfaatkan sesuai dengan strategi organisasi', 'description' => 'Mencari kesempatan penjualan baru dan memanfaatkan teknologi/alat baru sesuai strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_1, 'name' => '1.5 Potensi pendapatan diperkirakan, berdasarkan analisis informasi dan berkonsultasi dengan rekan kerja yang terkait', 'description' => 'Mengestimasi hasil penjualan yang dapat dicapai dengan analisis data dan masukan tim.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_1, 'name' => '1.6 Aktivitas direncanakan berdasarkan pola panggilan penjualan praktis yang maksimal untuk memenuhi target individu dan tim', 'description' => 'Menyusun jadwal panggilan penjualan yang efisien untuk mencapai target penjualan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_7,
            'title' => '2. Mempersiapkan panggilan penjualan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_7_2, 'name' => '2.1 Panggilan penjualan dilakukan sesuai dengan pola panggilan yang disepakati manajemen organisasi', 'description' => 'Melaksanakan panggilan penjualan mengikuti prosedur dan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_2, 'name' => '2.2 Strategi dan taktik panggilan penjualan dikembangkan berdasarkan pengetahuan pasar, fokus penjualan saat ini, dan konsultasi dengan rekan kerja', 'description' => 'Menyesuaikan pendekatan panggilan penjualan berdasarkan kondisi pasar dan target pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_2, 'name' => '2.3 Informasi dan materi pendukung dikumpulkan untuk mendukung panggilan penjualan', 'description' => 'Menyiapkan dokumen, presentasi, atau alat bantu lain untuk mendukung efektivitas panggilan penjualan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem7_7_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit7_7,
            'title' => '3. Melaksanakan pengelolaan teritori',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem7_7_3, 'name' => '3.1 Teritori yang menjadi tanggung jawab pekerjaannya dipetakan berdasarkan strategi organisasi', 'description' => 'Mengidentifikasi wilayah tanggung jawab dan membagi area kerja sesuai strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_3, 'name' => '3.2 Prioritas pelanggan ditentukan berdasarkan teritori', 'description' => 'Menentukan urutan kunjungan dan fokus pelanggan sesuai lokasi dan nilai potensial.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem7_7_3, 'name' => '3.3 Rencana kunjungan ditentukan berdasarkan teritori', 'description' => 'Membuat jadwal kunjungan pelanggan yang optimal berdasarkan peta teritori dan prioritas.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 7: PETUGAS PROMOSI PENJUALAN');

        // ── Skema 8: STAF HUBUNGAN MASYARAKAT ──
        $scheme8 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.008/XI/2023',
            'name' => 'STAF HUBUNGAN MASYARAKAT',
            'type' => 'Okupasi',
            'description' => 'Skema Staf Hubungan Masyarakat merupakan skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan dalam mengelola komunikasi organisasi secara efektif guna membangun dan menjaga citra serta reputasi organisasi. Skema ini mencakup kompetensi dalam mengidentifikasi elemen pemasaran organisasi, melaksanakan komunikasi efektif, serta melakukan penulisan bisnis sebagai sarana penyampaian pesan yang profesional dan terstruktur. Selain itu, peserta dibekali kemampuan menggunakan media sosial dan aplikasi daring, mempersiapkan konten digital, serta melaksanakan event sebagai bagian dari strategi komunikasi merek. Sertifikasi ini ditujukan bagi staf hubungan masyarakat yang berperan aktif dalam mendukung strategi komunikasi dan pemasaran organisasi sesuai dengan standar kompetensi kerja yang berlaku.',
            'certification_fee' => 1750000,
            'recertification_fee' => 1200000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit8_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme8,
            'unit_code' => 'M.70MKT00.001.2',
            'unit_title' => 'Melaksanakan Komunikasi Efektif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem8_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_1,
            'title' => '1. Mengidentifikasi gaya komunikasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_1_1, 'name' => '1.1 Gaya komunikasi pelanggan diidentifikasi sesuai dengan karakter pelanggan.', 'description' => 'Mengamati dan mengenali tipe komunikasi pelanggan (asertif, pasif, agresif, atau pasif-agresif) agar pendekatan komunikasi dapat disesuaikan secara tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_1_1, 'name' => '1.2 Teknik menghadapi pelanggan dengan gaya komunikasi yang berbeda-beda diterapkan sesuai dengan kebutuhan pelanggan.', 'description' => 'Menerapkan teknik komunikasi yang sesuai untuk setiap tipe pelanggan agar interaksi berjalan efektif dan profesional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_1_1, 'name' => '1.3 Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur organisasi.', 'description' => 'Menggunakan sarana komunikasi digital seperti telepon, email, dan aplikasi pesan sesuai standar dan aturan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_1,
            'title' => '2. Melaksanakan komunikasi langsung menggunakan bahasa lisan, tulisan, dan bahasa tubuh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_1_2, 'name' => '2.1 Pemilihan kata-kata saat berkomunikasi dilakukan secara cermat sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Memilih bahasa yang sopan, jelas, dan profesional agar pesan mudah dipahami dan tidak menimbulkan kesalahpahaman.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_1_2, 'name' => '2.2 Bahasa tubuh, bahasa tulisan, dan ekspresi wajah dikendalikan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Mengatur sikap tubuh, ekspresi wajah, serta gaya penulisan untuk mendukung pesan komunikasi secara positif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_1_2, 'name' => '2.3 Tata penampilan profesional dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Menjaga kerapihan dan kesesuaian penampilan agar mencerminkan sikap profesional dan meningkatkan kepercayaan pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_1_2, 'name' => '2.4 Komunikasi antara pelanggan dan staf dilaksanakan sesuai dengan karakter lawan bicara dan etika bisnis.', 'description' => 'Melaksanakan komunikasi dua arah yang sopan, terbuka, dan saling menghargai sesuai norma komunikasi bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_1_2, 'name' => '2.5 Kemampuan komunikasi ditingkatkan untuk meminimalkan kesalahpahaman antar komunikan untuk mencapai komunikasi yang efektif.', 'description' => 'Mengembangkan keterampilan komunikasi melalui evaluasi dan pembelajaran agar interaksi berjalan efektif dan efisien.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit8_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme8,
            'unit_code' => 'M.70MKT00.005.2',
            'unit_title' => 'Mengidentifikasi Elemen Pemasaran Organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem8_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_2,
            'title' => '1. Mengidentifikasi elemen strategi pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_2_1, 'name' => '1.1 Definisi segmentasi, targeting, dan positioning dijelaskan untuk memudahkan penentuan strategi pemasaran.', 'description' => 'Menjelaskan konsep segmentasi, targeting, dan positioning sebagai dasar dalam menentukan arah dan strategi pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_1, 'name' => '1.2 Segmentasi organisasi diuraikan sesuai dengan strategi organisasi.', 'description' => 'Mengelompokkan pasar berdasarkan kriteria yang relevan dan sesuai dengan tujuan serta strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_1, 'name' => '1.3 Target segmen diidentifikasi untuk memudahkan penyusunan strategi pemasaran.', 'description' => 'Menentukan segmen pasar utama yang akan dilayani sebagai fokus kegiatan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_1, 'name' => '1.4 Positioning yang ditetapkan dijabarkan sesuai dengan strategi organisasi.', 'description' => 'Menjelaskan posisi produk atau organisasi di benak konsumen sesuai dengan nilai dan keunggulan yang dimiliki.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_2,
            'title' => '2. Mengidentifikasi elemen taktik pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_2_2, 'name' => '2.1 Definisi diferensiasi, bauran pemasaran, dan penjualan dijelaskan untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Menjelaskan konsep diferensiasi, marketing mix, dan penjualan sebagai dasar penyusunan taktik pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_2, 'name' => '2.2 Kerangka diferensiasi secara infrastruktur (infrastructure), konten (content), dan konteks (context) diidentifikasi untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Mengidentifikasi bentuk diferensiasi organisasi dari sisi sarana, isi penawaran, dan konteks pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_2, 'name' => '2.3 Elemen-elemen dalam bauran pemasaran dijabarkan untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Menguraikan elemen produk, harga, distribusi, dan promosi sebagai bagian dari strategi taktis pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_2, 'name' => '2.4 Tipe-tipe pendekatan penjualan diidentifikasi untuk memudahkan penyusunan taktik pemasaran.', 'description' => 'Mengidentifikasi metode dan pendekatan penjualan yang sesuai dengan karakter produk dan target pasar.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_2,
            'title' => '3. Mengidentifikasi elemen nilai pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_2_3, 'name' => '3.1 Definisi merek (brand), layanan (service), dan proses (process) dijelaskan sesuai dengan nilai organisasi.', 'description' => 'Menjelaskan konsep merek, layanan, dan proses sebagai bagian dari nilai yang dijunjung oleh organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_3, 'name' => '3.2 Ekuitas merek (brand equity) dan elemen pembentuknya dijelaskan agar sesuai dengan nilai organisasi.', 'description' => 'Menguraikan komponen pembentuk kekuatan merek seperti kesadaran merek, persepsi kualitas, dan loyalitas pelanggan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_2_3, 'name' => '3.3 Dimensi kualitas pelayanan (service quality) dijabarkan agar tetap relevan dengan nilai organisasi.', 'description' => 'Menjelaskan dimensi kualitas pelayanan seperti keandalan, daya tanggap, jaminan, empati, dan bukti fisik.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit8_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme8,
            'unit_code' => 'M.70MKT00.012.1',
            'unit_title' => 'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem8_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_3,
            'title' => '1. Menentukan kebutuhan organisasi dan komunikasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_3_1, 'name' => '1.1 Rancangan aksi strategis ditentukan untuk mengarahkan aktivitas.', 'description' => 'Menyusun rencana aksi strategis sebagai panduan pelaksanaan aktivitas komunikasi dan pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_1, 'name' => '1.2 Target audience dan tujuan penggunaan media sosial diidentifikasi sesuai dengan strategi pemasaran.', 'description' => 'Menentukan sasaran audiens serta tujuan penggunaan media sosial agar selaras dengan strategi pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_1, 'name' => '1.3 Media sosial dipilih yang paling cocok dengan tujuan komunikasi dan target pelanggan.', 'description' => 'Memilih platform media sosial yang paling efektif untuk menjangkau target pelanggan dan mencapai tujuan komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_1, 'name' => '1.4 Daftar tipe media sosial dan media komunikasi daring yang sesuai dibuat berdasarkan strategi pemasaran.', 'description' => 'Menyusun daftar platform dan media komunikasi daring yang akan digunakan sesuai rencana pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_3,
            'title' => '2. Mempersiapkan pilar konten',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_3_2, 'name' => '2.1 Sumber informasi eksternal dipilih yang relevan dengan rencana komunikasi.', 'description' => 'Menentukan sumber informasi eksternal yang valid dan relevan sebagai bahan pendukung konten komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_2, 'name' => '2.2 Bahasa, gaya, dan nada komunikasi digunakan sesuai dengan target konsumen.', 'description' => 'Menyesuaikan gaya bahasa, nada, dan penyampaian pesan dengan karakter dan preferensi target konsumen.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_2, 'name' => '2.3 Konten dicek untuk melihat kesesuaian dengan tujuan awal.', 'description' => 'Melakukan pengecekan konten agar tetap selaras dengan tujuan komunikasi dan strategi yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_2, 'name' => '2.4 Persetujuan didapatkan sebelum publikasi.', 'description' => 'Memastikan konten memperoleh persetujuan dari pihak berwenang sebelum dipublikasikan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_3_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_3,
            'title' => '3. Memanfaatkan fungsi platform',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_3_3, 'name' => '3.1 Format yang sudah tersedia di platform daring dimanfaatkan untuk membuat post.', 'description' => 'Menggunakan format bawaan platform (teks, gambar, video, stories, dll.) secara optimal dalam pembuatan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_3, 'name' => '3.2 Tipe file yang sesuai diunggah berdasarkan prosedur.', 'description' => 'Mengunggah jenis file yang sesuai dengan ketentuan teknis dan prosedur platform serta organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_3, 'name' => '3.3 Tautan aktif ke situs organisasi dan eksternal dibuat sesuai prosedur.', 'description' => 'Menyertakan tautan yang berfungsi dengan baik dan sesuai kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_3, 'name' => '3.4 Algoritma platform diidentifikasi dalam optimasi pemanfaatan.', 'description' => 'Memahami dan memanfaatkan algoritma platform untuk meningkatkan jangkauan dan efektivitas konten.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_3_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_3,
            'title' => '4. Mempromosikan keterikatan (engagement) kepada organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_3_4, 'name' => '4.1 Perencanaan konten dikelola secara berkala berdasarkan jadwal yang sudah ada.', 'description' => 'Mengelola dan menyesuaikan rencana konten secara rutin sesuai kalender dan jadwal yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_4, 'name' => '4.2 Post digunakan selama kegiatan pemasaran berlangsung.', 'description' => 'Memanfaatkan konten secara konsisten selama periode kampanye atau aktivitas pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_3_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_3,
            'title' => '5. Memantau keterlibatan organisasi dengan pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_3_5, 'name' => '5.1 Aktivitas pelanggan dipantau interaksinya.', 'description' => 'Memantau respons dan interaksi pelanggan seperti komentar, pesan, dan reaksi di media sosial.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_5, 'name' => '5.2 Komunikasi dengan pelanggan dilakukan dalam waktu yang tepat secara profesional.', 'description' => 'Menanggapi pelanggan secara cepat, sopan, dan profesional sesuai etika komunikasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_5, 'name' => '5.3 Komplain dan komentar negatif direspon dengan tepat berdasarkan prosedur yang telah ditetapkan.', 'description' => 'Menangani keluhan dan komentar negatif sesuai SOP agar reputasi organisasi tetap terjaga.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_3_5, 'name' => '5.4 Masukan pelanggan terhadap personil terkait dirujuk sesuai kebijakan dan prosedur.', 'description' => 'Menyampaikan masukan pelanggan kepada pihak terkait untuk tindak lanjut sesuai kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit8_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme8,
            'unit_code' => 'M.70MKT00.002.2',
            'unit_title' => 'Melaksanakan Penulisan Bisnis (Business Writing)',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem8_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_4,
            'title' => '1. Mempersiapkan topik pesan bisnis yang akan disampaikan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_4_1, 'name' => '1.1 Tujuan penulisan bisnis ditentukan sesuai dengan topik pesan yang ingin disampaikan', 'description' => 'Menetapkan tujuan penulisan pesan bisnis (informasi, permintaan, penawaran, atau konfirmasi) agar isi pesan fokus dan tepat sasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_4_1, 'name' => '1.2 Kerangka pesan bisnis yang dapat menggambarkan kalimat pembuka, pesan inti, serta kalimat penutup dipersiapkan sesuai dengan topik pesan', 'description' => 'Menyusun struktur pesan bisnis yang sistematis agar alur komunikasi jelas dan mudah dipahami penerima.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_4,
            'title' => '2. Menuliskan pesan bisnis sesuai topik',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_4_2, 'name' => '2.1 Nama, jabatan, serta nama organisasi dituliskan sesuai dengan prosedur organisasi', 'description' => 'Mencantumkan identitas pengirim secara lengkap dan resmi sesuai standar penulisan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_4_2, 'name' => '2.2 Kerangka pesan bisnis yang ada dituliskan sesuai dengan topik pesan', 'description' => 'Mengembangkan kerangka pesan menjadi teks utuh yang relevan dengan tujuan dan topik pesan bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_4_2, 'name' => '2.3 Pesan bisnis dituliskan dengan bahasa yang baku dan mudah dimengerti', 'description' => 'Menggunakan bahasa formal, jelas, ringkas, dan tidak menimbulkan penafsiran ganda.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_4,
            'title' => '3. Mengevaluasi pesan bisnis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_4_3, 'name' => '3.1 Tulisan, huruf dan bentuk dokumen diperiksa sesuai ketentuan organisasi', 'description' => 'Memastikan format dokumen, jenis huruf, ukuran, dan tata letak telah sesuai standar organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_4_3, 'name' => '3.2 Tercapainya tujuan penulisan bisnis dikonfirmasikan kepada penerima surat', 'description' => 'Memastikan pesan bisnis dipahami dan tujuan komunikasi tercapai melalui umpan balik penerima.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_4_3, 'name' => '3.3 Dokumen bisnis diperiksa kembali sebelum terkirim', 'description' => 'Melakukan pengecekan akhir terhadap isi, bahasa, dan kelengkapan dokumen sebelum dikirim.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit8_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme8,
            'unit_code' => 'M.70MKT00.011.2',
            'unit_title' => 'Melaksanakan Event Sebagai Bagian dari  Komunikasi Merek',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem8_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_5,
            'title' => '1. Merencanakan event',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_5_1, 'name' => '1.1 Tujuan event ditetapkan berdasarkan rencana pemasaran', 'description' => 'Menentukan tujuan penyelenggaraan event yang selaras dengan strategi dan sasaran pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_5_1, 'name' => '1.2 Term of Reference (TOR) disusun berdasarkan rencana pemasaran', 'description' => 'Menyusun TOR sebagai acuan pelaksanaan event yang memuat latar belakang, tujuan, ruang lingkup, dan indikator keberhasilan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_5_1, 'name' => '1.3 Aspek biaya dianggarkan berdasarkan rencana pemasaran', 'description' => 'Menghitung dan menetapkan anggaran event secara rinci dan realistis sesuai kebutuhan dan kemampuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_5_1, 'name' => '1.4 Proposal event dibuat berdasarkan rencana pemasaran', 'description' => 'Menyusun proposal event secara sistematis untuk mendapatkan persetujuan dan dukungan dari pihak terkait.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_5,
            'title' => '2. Menangani acara pada event',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_5_2, 'name' => '2.1 Pesan utama dari penyelenggaraan event disampaikan dalam bentuk rangkaian kegiatan', 'description' => 'Memastikan pesan inti event tersampaikan melalui susunan acara dan aktivitas yang dirancang secara efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_5_2, 'name' => '2.2 Target audience dicapai sesuai tujuan yang telah ditetapkan', 'description' => 'Menyelenggarakan event agar menjangkau dan melibatkan audiens sasaran sesuai dengan tujuan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_5,
            'title' => '3. Mengevaluasi penyelenggaraan event',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_5_3, 'name' => '3.1 Tolok ukur untuk mengevaluasi performa event dianalisis berdasarkan tujuan penyelenggaraan', 'description' => 'Menentukan dan menganalisis indikator keberhasilan event seperti jumlah peserta, engagement, dan dampak pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit8_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme8,
            'unit_code' => 'M.70MKT00.014.1',
            'unit_title' => 'Mempersiapkan Konten Digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem8_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_6,
            'title' => '1. Menentukan kebutuhan konten digital',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_6_1, 'name' => '1.1 Kebutuhan konten tertulis dan visual secara digital ditentukan sesuai tujuan pemasaran.', 'description' => 'Menentukan jenis konten teks dan visual yang diperlukan agar selaras dengan tujuan dan strategi pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_1, 'name' => '1.2 Format konten ditinjau untuk menjaga konsistensi.', 'description' => 'Meninjau format konten agar konsisten dengan identitas merek dan standar komunikasi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_1, 'name' => '1.3 Fungsi dan batasan platform diidentifikasi untuk pengembangan konten yang sesuai.', 'description' => 'Mengidentifikasi kemampuan dan keterbatasan tiap platform digital agar konten sesuai secara teknis dan fungsional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_1, 'name' => '1.4 Platform untuk konten internal dan eksternal ditentukan agar batas penggunaan setiap platform jelas.', 'description' => 'Menentukan platform yang digunakan untuk kebutuhan internal dan eksternal agar tidak terjadi tumpang tindih penggunaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_1, 'name' => '1.5 Informasi produk untuk pengembangan konten disesuaikan dengan tujuan pemasaran.', 'description' => 'Menyesuaikan informasi produk yang akan digunakan dalam konten dengan sasaran dan pesan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_1, 'name' => '1.6 Pengembangan konten direncanakan agar sejalan dengan citra dan aktivitas pemasaran.', 'description' => 'Menyusun rencana pengembangan konten yang mendukung citra merek dan kegiatan pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_6,
            'title' => '2. Mengembangkan konten tertulis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_6_2, 'name' => '2.1 Konten tertulis dikembangkan sesuai dengan citra dan aktivitas pemasaran.', 'description' => 'Menghasilkan konten tulisan yang mencerminkan identitas merek dan mendukung aktivitas pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_2, 'name' => '2.2 Konten tertulis dihasilkan secara akurat dan rinci.', 'description' => 'Menyusun konten tulisan dengan informasi yang benar, jelas, dan detail.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_2, 'name' => '2.3 Gaya penulisan digunakan dengan jelas sesuai target pasar.', 'description' => 'Menggunakan gaya bahasa yang sesuai dengan karakter dan preferensi target audiens.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_2, 'name' => '2.4 Teknik penulisan warta (copywriting) digunakan berdasarkan target pasar.', 'description' => 'Menerapkan teknik copywriting untuk menarik perhatian dan mendorong respons audiens.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_2, 'name' => '2.5 Kata kunci digunakan dalam penelusuran daring secara optimal.', 'description' => 'Menggunakan kata kunci yang relevan untuk meningkatkan visibilitas konten di mesin pencari.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_2, 'name' => '2.6 Susunan kalimat dan ejaan yang benar digunakan sesuai dengan isi pesan.', 'description' => 'Memastikan struktur kalimat dan ejaan sesuai kaidah bahasa agar pesan mudah dipahami.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_2, 'name' => '2.7 Masukan dari pihak lain dipertimbangkan relevansinya dan perubahan konten disusun berdasarkan tujuan pemasaran.', 'description' => 'Menyesuaikan konten tertulis berdasarkan masukan pihak terkait agar tetap sesuai tujuan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem8_6_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit8_6,
            'title' => '3. Mengembangkan konten visual',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem8_6_3, 'name' => '3.1 Konten visual yang sesuai digunakan dengan memperhatikan citra dan gaya pemasaran.', 'description' => 'Menggunakan visual yang konsisten dengan identitas merek dan gaya pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_3, 'name' => '3.2 Konten visual diedit guna meningkatkan kualitas dan daya tarik visual.', 'description' => 'Melakukan pengeditan visual untuk meningkatkan estetika dan kualitas tampilan konten.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_3, 'name' => '3.3 Konten visual yang mempromosikan produk dan jasa ditentukan sesuai tujuan.', 'description' => 'Menentukan visual yang efektif untuk mendukung promosi produk atau jasa.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem8_6_3, 'name' => '3.4 Komentar dari pihak terlibat digunakan sebagai dasar perbaikan konten visual.', 'description' => 'Menyesuaikan konten visual berdasarkan masukan pihak terkait agar hasil lebih optimal.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 8: STAF HUBUNGAN MASYARAKAT');

        // ── Skema 9: MANAJER PENGEMBANGAN BISNIS ──
        $scheme9 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.009/XI/2023',
            'name' => 'MANAJER PENGEMBANGAN BISNIS',
            'type' => 'Okupasi',
            'description' => 'Skema Manajer Pengembangan Bisnis merupakan skema sertifikasi kompetensi yang dirancang untuk memastikan tenaga kerja memiliki kemampuan strategis dalam mengidentifikasi peluang, mengembangkan, serta mengimplementasikan strategi bisnis yang berkelanjutan. Skema ini mencakup kompetensi dalam melakukan analisis lingkungan bisnis, menyusun dan menerapkan strategi bisnis, serta mengelola lingkungan layanan (service environment) guna meningkatkan nilai dan daya saing organisasi. Selain itu, peserta dibekali kemampuan dalam mengidentifikasi dan menyusun elemen pemasaran organisasi serta mengelola program loyalitas pelanggan sebagai upaya membangun hubungan jangka panjang dengan pasar. Sertifikasi ini ditujukan bagi manajer pengembangan bisnis yang berperan dalam mendorong pertumbuhan usaha, optimalisasi kinerja pemasaran, dan peningkatan kepuasan pelanggan sesuai standar kompetensi kerja yang berlaku.',
            'certification_fee' => 3500000,
            'recertification_fee' => 2750000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit9_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme9,
            'unit_code' => 'M.70MKT00.003.2',
            'unit_title' => 'Melaksanakan Analisis Lingkungan Bisnis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem9_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_1,
            'title' => '1. Mengidentifikasi perubahan lingkungan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_1_1, 'name' => '1.1 Perubahan teknologi, sosial budaya, ekonomi, politik legal dan perubahan pasar diidentifikasi sesuai dengan kebutuhan organisasi', 'description' => 'Mengidentifikasi berbagai perubahan lingkungan eksternal yang dapat memengaruhi kinerja dan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_1, 'name' => '1.2 Kebijakan organisasi secara makro dikaji ulang kesesuaian dan relevansinya dengan kondisi makro terkini', 'description' => 'Meninjau kembali kebijakan organisasi agar tetap relevan dengan perkembangan lingkungan eksternal terbaru.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_1,
            'title' => '2. Menjabarkan situasi persaingan pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_1_2, 'name' => '2.1 Informasi pesaing dicari berdasarkan kebutuhan pasar', 'description' => 'Mengumpulkan data dan informasi mengenai pesaing untuk memahami posisi persaingan di pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_2, 'name' => '2.2 Informasi pesaing dianalisis berdasarkan kebutuhan pasar', 'description' => 'Menganalisis kekuatan, kelemahan, dan strategi pesaing sesuai dengan dinamika kebutuhan pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_2, 'name' => '2.3 Hasil analisis informasi pesaing dievaluasi berdasarkan kebutuhan pasar', 'description' => 'Mengevaluasi hasil analisis pesaing sebagai dasar pengambilan keputusan strategis pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_1_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_1,
            'title' => '3. Mengidentifikasi situasi pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_1_3, 'name' => '3.1 Situasi pelanggan dari segi kognitif (cognitive), konatif (conative), dan afektif (affective) diidentifikasi berdasarkan kebutuhan pasar', 'description' => 'Mengidentifikasi persepsi, sikap, dan perilaku pelanggan untuk memahami kebutuhan dan preferensi pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_3, 'name' => '3.2 Pangsa pasar (market share), ukuran pasar (market size), pertumbuhan pasar (market growth) terhadap pelanggan dievaluasi berdasarkan kebutuhan organisasi', 'description' => 'Mengevaluasi kondisi pasar secara kuantitatif untuk menentukan potensi dan peluang bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_1_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_1,
            'title' => '4. Melakukan analisis komprehensif terhadap organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_1_4, 'name' => '4.1 Sumber daya, capability, dan kompetensi diidentifikasi secara komprehensif', 'description' => 'Mengidentifikasi seluruh sumber daya dan kemampuan organisasi sebagai dasar penentuan strategi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_4, 'name' => '4.2 Situasi internal (kekuatan dan kelemahan) organisasi dikaji ulang secara komprehensif', 'description' => 'Menganalisis faktor internal organisasi guna mengetahui posisi strategis organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_4, 'name' => '4.3 Fokus strategis organisasi ditetapkan sesuai dengan kebutuhan pasar', 'description' => 'Menentukan fokus strategi organisasi agar selaras dengan kondisi dan tuntutan pasar.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_1_5 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_1,
            'title' => '5. Melaksanakan analisis ancaman, kesempatan, kelemahan, dan kekuatan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_1_5, 'name' => '5.1 Ancaman (threat), kesempatan (opportunity), kelemahan (weakness), dan kekuatan (strength) organisasi diidentifikasi berdasarkan kesesuaian dan relevansinya dengan kondisi makro terkini', 'description' => 'Mengidentifikasi faktor internal dan eksternal organisasi melalui analisis SWOT sebagai dasar perumusan strategi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_5, 'name' => '5.2 Isu utama diidentifikasi berdasarkan strategi organisasi', 'description' => 'Menentukan isu-isu strategis utama yang berdampak signifikan terhadap pencapaian tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_5, 'name' => '5.3 Implikasi dari isu utama diidentifikasi berdasarkan strategi organisasi', 'description' => 'Menganalisis dampak dan konsekuensi dari isu utama terhadap arah dan kebijakan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_1_5, 'name' => '5.4 Tujuan strategis pemasaran organisasi dirumuskan sesuai dengan kebutuhan dan situasi makro terkini', 'description' => 'Merumuskan tujuan strategis pemasaran yang relevan dengan kondisi lingkungan dan kebutuhan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit9_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme9,
            'unit_code' => 'M.70MKT00.004.1',
            'unit_title' => 'Mengembangkan dan Menerapkan Strategi Bisnis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem9_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_2,
            'title' => '1. Menyeleksi model bisnis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_2_1, 'name' => '1.1 Pendekatan bisnis B2B, B2C, aplikasi bisnis internal serta model bisnis diidentifikasi berdasarkan prosedur organisasi.', 'description' => 'Mengidentifikasi berbagai pendekatan dan model bisnis yang dapat diterapkan sesuai dengan ketentuan dan kebutuhan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_1, 'name' => '1.2 Model bisnis diberi peringkat berdasarkan kekuatan dan kelemahannya dengan mempertimbangkan sumber daya, persyaratan teknis, dan keamanan.', 'description' => 'Melakukan analisis perbandingan model bisnis untuk menentukan keunggulan dan keterbatasan masing-masing model.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_1, 'name' => '1.3 Implikasi biaya dari implementasi model bisnis dinilai untuk mendapatkan hasil yang paling optimal.', 'description' => 'Menghitung dan menganalisis biaya implementasi agar diperoleh efisiensi dan efektivitas bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_1, 'name' => '1.4 Model bisnis yang paling sesuai dengan strategi organisasi ditentukan sesuai dengan visi organisasi.', 'description' => 'Menetapkan model bisnis terbaik yang mendukung visi, misi, dan strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_2,
            'title' => '2. Merancang strategi bisnis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_2_2, 'name' => '2.1 Tujuan, sasaran, dan nilai bisnis dirumuskan agar sesuai dengan model bisnis.', 'description' => 'Menyusun arah dan tujuan bisnis yang jelas sebagai pedoman pelaksanaan strategi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_2, 'name' => '2.2 Target pasar dan struktur rantai nilai bisnis diidentifikasi agar sesuai dengan model bisnis.', 'description' => 'Menentukan segmen pasar dan alur nilai untuk memastikan strategi bisnis berjalan efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_2, 'name' => '2.3 Kebutuhan teknis dan keahlian dalam mengimplementasikan model bisnis diidentifikasi untuk mengurangi risiko implementasi.', 'description' => 'Mengidentifikasi sumber daya manusia dan teknologi yang dibutuhkan untuk mendukung pelaksanaan strategi bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_2, 'name' => '2.4 Rencana transisi ke sistem pemasaran elektronik (digital) dikembangkan untuk mengurangi risiko implementasi.', 'description' => 'Menyusun langkah-langkah peralihan ke sistem digital agar proses bisnis tetap berjalan lancar.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_2,
            'title' => '3. Mengimplementasikan strategi bisnis',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_2_3, 'name' => '3.1 Kebijakan dan pedoman untuk mendukung pelanggan, rantai pasok, dan staf dikembangkan.', 'description' => 'Menyusun aturan dan pedoman kerja sebagai acuan implementasi strategi bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_3, 'name' => '3.2 Kinerja tujuan bisnis, kebijakan, dan prosedur disusun untuk menanggapi perubahan kebutuhan pelanggan, staf, dan rantai pasok.', 'description' => 'Menyesuaikan kebijakan dan prosedur agar tetap responsif terhadap perubahan lingkungan bisnis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_3, 'name' => '3.3 Sistem dan model bisnis dikaji ulang agar tetap relevan dengan kondisi terkini.', 'description' => 'Melakukan evaluasi berkala terhadap sistem dan model bisnis yang diterapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_2_3, 'name' => '3.4 Hasil evaluasi dan umpan balik diolah untuk meningkatkan strategi bisnis di masa depan.', 'description' => 'Menggunakan hasil evaluasi sebagai dasar perbaikan dan pengembangan strategi bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit9_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme9,
            'unit_code' => 'M.70MKT00.005.2',
            'unit_title' => 'Mengidentifikasi Elemen Pemasaran  Organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem9_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_3,
            'title' => '1. Mengidentifikasi elemen-elemen utama lingkungan layanan yang paling berpengaruh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_3_1, 'name' => '1.1 Analisis tujuan strategis pelayanan organisasi dilakukan berdasarkan rencana strategis organisasi.', 'description' => 'Menganalisis arah dan tujuan pelayanan organisasi sebagai dasar dalam menentukan lingkungan layanan yang tepat.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_3_1, 'name' => '1.2 Kebutuhan lingkungan layanan dari segmen utama konsumen diidentifikasi sesuai dengan kondisi pasar terkini.', 'description' => 'Mengidentifikasi kebutuhan dan harapan konsumen terhadap lingkungan layanan berdasarkan tren dan kondisi pasar terbaru.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_3_1, 'name' => '1.3 Elemen-elemen lingkungan layanan diurutkan berdasarkan kesesuaian dengan tujuan organisasi dan kebutuhan konsumen.', 'description' => 'Menentukan prioritas elemen lingkungan layanan yang paling berpengaruh terhadap kepuasan konsumen dan pencapaian tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_3,
            'title' => '2. Menyusun rancangan pengaturan fasilitas dan perlengkapan sarana pendukung pelayanan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_3_2, 'name' => '2.1 Spesifikasi rinci ditentukan dari setiap elemen lingkungan layanan yang sesuai dengan analisis sebelumnya.', 'description' => 'Menetapkan detail spesifikasi fasilitas dan perlengkapan layanan sesuai hasil analisis kebutuhan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_3_2, 'name' => '2.2 Proses menjaga konsistensi lingkungan layanan dirancang berdasarkan prosedur operasional.', 'description' => 'Merancang alur kerja dan standar operasional untuk menjaga keseragaman dan kualitas lingkungan layanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_3_2, 'name' => '2.3 Komunikasi dengan pihak-pihak yang terlibat dilakukan secara berkala.', 'description' => 'Melakukan koordinasi rutin dengan pihak terkait agar pelaksanaan rancangan lingkungan layanan berjalan efektif.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_3_2, 'name' => '2.4 Proses menjaga konsistensi lingkungan layanan dilakukan berdasarkan prosedur operasional.', 'description' => 'Melaksanakan pengendalian dan pemantauan lingkungan layanan agar tetap sesuai standar yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_3_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_3,
            'title' => '3. Memantau implementasi rancangan pengaturan fasilitas pendukung',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_3_3, 'name' => '3.1 Kriteria penilaian kinerja lingkungan layanan ditentukan berdasarkan tujuan strategis pelayanan organisasi.', 'description' => 'Menentukan indikator penilaian untuk mengukur efektivitas dan kualitas lingkungan layanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_3_3, 'name' => '3.2 Pengumpulan data kualitas kinerja lingkungan layanan dilakukan secara berkala.', 'description' => 'Mengumpulkan dan mendokumentasikan data kinerja lingkungan layanan sebagai bahan evaluasi dan perbaikan berkelanjutan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit9_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme9,
            'unit_code' => 'M.70MKT00.006.2',
            'unit_title' => 'Menyusun Elemen Pemasaran Organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem9_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_4,
            'title' => '1. Merencanakan elemen strategi pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_4_1, 'name' => '1.1 Segmentasi organisasi dievaluasi agar tetap relevan dengan kondisi terkini.', 'description' => 'Menilai kembali segmentasi pasar organisasi agar sesuai dengan perubahan lingkungan bisnis dan perilaku konsumen.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_1, 'name' => '1.2 Target segmen ditentukan berdasarkan strategi pemasaran.', 'description' => 'Menetapkan segmen pasar utama yang akan dilayani sesuai arah dan tujuan strategi pemasaran organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_1, 'name' => '1.3 Posisi produk dalam pasar (positioning) ditetapkan dengan menggunakan kerangka kerja yang ada.', 'description' => 'Menentukan posisi produk di benak konsumen menggunakan kerangka positioning yang relevan agar memiliki keunggulan bersaing.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_4,
            'title' => '2. Menentukan elemen taktik pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_4_2, 'name' => '2.1 Kerangka diferensiasi disusun secara infrastruktur (infrastructure), konten (content), dan konteks (context).', 'description' => 'Menyusun pembeda produk atau merek dari sisi sarana pendukung, isi pesan, dan konteks komunikasi pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_2, 'name' => '2.2 Ruang lingkup produk ditetapkan berdasarkan strategi pemasaran organisasi.', 'description' => 'Menentukan variasi, fitur, dan cakupan produk sesuai strategi pemasaran yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_2, 'name' => '2.3 Saluran distribusi (distribution channel) ditetapkan berdasarkan strategi pemasaran organisasi.', 'description' => 'Menentukan jalur distribusi produk agar efektif menjangkau target pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_2, 'name' => '2.4 Metode penetapan harga pada merek ditentukan berdasarkan strategi pemasaran organisasi.', 'description' => 'Menetapkan strategi harga yang sesuai dengan nilai merek, target pasar, dan tujuan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_2, 'name' => '2.5 Bentuk promosi terhadap merek ditentukan berdasarkan strategi pemasaran organisasi, termasuk penentuan media promosi.', 'description' => 'Menentukan jenis promosi dan media yang digunakan untuk mendukung pencapaian tujuan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_2, 'name' => '2.6 Bentuk teknik penjualan ditentukan dalam rangka penyusunan taktik pemasaran.', 'description' => 'Menentukan pendekatan dan teknik penjualan yang paling efektif untuk mendukung strategi pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_4_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_4,
            'title' => '3. Mengkaji ulang elemen nilai pemasaran organisasi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_4_3, 'name' => '3.1 Definisi merek (brand), layanan (service), proses (process) dijelaskan sesuai dengan nilai organisasi.', 'description' => 'Menjelaskan konsep merek, layanan, dan proses agar sejalan dengan nilai inti organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_3, 'name' => '3.2 Ekuitas merek (brand equity) dan elemen pembentuknya dijelaskan agar sesuai dengan nilai organisasi.', 'description' => 'Menguraikan nilai tambah merek dan faktor pembentuknya agar mencerminkan identitas organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_3, 'name' => '3.3 Ciri khas merek (brand personality) dan elemen pembentuknya dijelaskan agar sesuai dengan nilai organisasi.', 'description' => 'Menjelaskan karakter merek yang membedakan organisasi dari pesaing.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_3, 'name' => '3.4 Dimensi kualitas pelayanan (service quality) dijabarkan agar tetap relevan dengan nilai organisasi.', 'description' => 'Menguraikan dimensi kualitas layanan untuk memastikan konsistensi dengan nilai organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_4_3, 'name' => '3.5 Konsep quality, cost, delivery dan kaitannya dengan proses diuraikan agar menghasilkan proses pemasaran yang optimal.', 'description' => 'Menjelaskan hubungan kualitas, biaya, dan ketepatan layanan dalam mendukung efektivitas proses pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit9_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme9,
            'unit_code' => 'M.70MKT00.021.2',
            'unit_title' => 'Mengelola Program Loyalitas Pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem9_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_5,
            'title' => '1. Mengidentifikasi tujuan program loyalitas',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_5_1, 'name' => '1.1 Analisis dilakukan untuk memahami kelompok pelanggan berdasarkan loyalitas.', 'description' => 'Melakukan pengelompokan pelanggan berdasarkan tingkat dan pola loyalitas untuk memahami karakteristik masing-masing kelompok.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_1, 'name' => '1.2 Nilai (value) dari kelompok pelanggan dianalisis berdasarkan loyalitas.', 'description' => 'Menganalisis kontribusi nilai pelanggan terhadap organisasi berdasarkan tingkat loyalitasnya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_1, 'name' => '1.3 Analisis dilakukan terhadap tingkat dan jenis loyalitas yang ingin dicapai organisasi.', 'description' => 'Menentukan jenis loyalitas (emosional, perilaku, atau kombinasi) serta tingkat loyalitas yang ditargetkan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_5,
            'title' => '2. Membangunan kerangka program loyalitas pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_5_2, 'name' => '2.1 Jenis-jenis program loyalitas dipilih sesuai dengan analisis pelanggan dan tujuan program.', 'description' => 'Menentukan bentuk program loyalitas yang paling sesuai dengan karakteristik pelanggan dan tujuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_2, 'name' => '2.2 Rencana implementasi program loyalitas dijabarkan secara detail.', 'description' => 'Menyusun langkah-langkah pelaksanaan program loyalitas secara sistematis dan terstruktur.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_2, 'name' => '2.3 Target loyalitas yang hendak dicapai dalam rentang waktu tertentu ditentukan berdasarkan strategi organisasi.', 'description' => 'Menetapkan target capaian loyalitas pelanggan dalam periode waktu tertentu sesuai strategi organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_2, 'name' => '2.4 Mekanisme monitoring ditentukan terhadap kinerja program.', 'description' => 'Menentukan metode dan indikator pemantauan untuk menilai efektivitas program loyalitas.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem9_5_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit9_5,
            'title' => '3. Menerapkan program loyalitas pelanggan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem9_5_3, 'name' => '3.1 Komunikasi dan koordinasi dilakukan dengan pihak yang terlibat dalam penerapan program loyalitas.', 'description' => 'Melakukan koordinasi dengan pihak internal maupun eksternal agar program loyalitas berjalan sesuai rencana.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_3, 'name' => '3.2 Informasi mengenai program loyalitas dikomunikasikan kepada pelanggan.', 'description' => 'Menyampaikan informasi program loyalitas secara jelas dan menarik kepada pelanggan sasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem9_5_3, 'name' => '3.3 Pemantauan berkala dilaksanakan terhadap kinerja program loyalitas.', 'description' => 'Melakukan evaluasi rutin terhadap pelaksanaan dan hasil program loyalitas untuk perbaikan berkelanjutan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 9: MANAJER PENGEMBANGAN BISNIS');

        // ── Skema 10: JENJANG IV BIDANG KEWIRAUSAHAAN INDUSTRI ──
        $scheme10 = DB::table('schemes')->insertGetId([
            'code' => '09-10/SSO/MKC.SKM.010/XI/2023',
            'name' => 'JENJANG IV BIDANG KEWIRAUSAHAAN INDUSTRI',
            'type' => 'KKNI',
            'description' => 'Skema Jenjang IV Bidang Kewirausahaan Industri merupakan skema sertifikasi yang dirancang untuk memastikan kompetensi pelaku usaha industri dalam mengelola kegiatan kewirausahaan secara menyeluruh, mulai dari tahap perencanaan hingga pemasaran produk. Skema ini mencakup kemampuan melakukan survei pasar dan survei pedagang eceran, studi proses produksi, serta survei sumber bahan baku dan bahan pembantu sebagai dasar pengambilan keputusan usaha. Peserta juga dibekali kompetensi dalam menganalisis harga pokok produksi, menentukan jenis produk yang akan diusahakan, melakukan pengadaan mesin, peralatan, bahan baku, dan bahan pembantu, serta mengatur penyimpanan bahan dan produk sesuai standar. Selain itu, skema ini menekankan kemampuan pemeriksaan mutu bahan, pelaksanaan promosi produk, dan kegiatan pemasaran produk secara efektif untuk mendukung keberlanjutan dan daya saing usaha industri. Sertifikasi ini ditujukan bagi wirausaha industri pemula hingga berkembang, calon pelaku usaha industri, pelaku UMKM sektor industri, serta tenaga kerja atau pendamping usaha yang terlibat langsung dalam kegiatan produksi, pengadaan, dan pemasaran produk industri.',
            'certification_fee' => 3500000,
            'recertification_fee' => 2750000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $unit10_1 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.001.01',
            'unit_title' => 'Melaksanakan survey pasar atas suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_1_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_1,
            'title' => '1. Menyiapkan pelaksanaan survey pasar atas suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_1_1, 'name' => '1.1 Pengetahuan dasar tentang survey dijelaskan.', 'description' => 'Menjelaskan konsep dasar survei pasar, termasuk pengertian, tujuan, dan manfaat survei sebagai dasar pengambilan keputusan pemasaran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_1, 'name' => '1.2 Masalah yang akan disurvey diidentifikasi.', 'description' => 'Mengidentifikasi permasalahan pasar yang menjadi fokus survei agar data yang dikumpulkan relevan dengan kebutuhan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_1, 'name' => '1.3 Tujuan survey pasar ditentukan untuk dasar pelaksanaan survey.', 'description' => 'Menentukan tujuan survei pasar secara jelas sebagai acuan dalam penyusunan rencana dan metode survei.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_1_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_1,
            'title' => '2. Menyusun rencana survey pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_1_2, 'name' => '2.1 Lokasi dan sumber data ditentukan berdasarkan lokasi pemasaran produk yang akan dibuat.', 'description' => 'Menentukan lokasi survei dan sumber data yang sesuai dengan wilayah pemasaran produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_2, 'name' => '2.2 Metoda survey ditentukan berdasarkan tujuan survey.', 'description' => 'Memilih metode survei (misalnya wawancara, kuesioner, observasi) yang paling sesuai untuk mencapai tujuan survei.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_2, 'name' => '2.3 Biaya pelaksanaan survey pasar dihitung berdasarkan kegiatan yang akan dilaksanakan dan biaya per kegiatan.', 'description' => 'Menghitung anggaran survei secara rinci sesuai aktivitas yang dilakukan agar pelaksanaan survei efisien dan terkontrol.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_2, 'name' => '2.4 Alat untuk pengumpulan data dalam pelaksanaan survey pasar disiapkan.', 'description' => 'Menyiapkan instrumen survei seperti kuesioner, formulir, atau perangkat digital untuk pengumpulan data.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_1_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_1,
            'title' => '3. Melaksanakan survey pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_1_3, 'name' => '3.1 Spesifikasi dan harga setiap jenis produk dikumpulkan.', 'description' => 'Mengumpulkan data terkait spesifikasi dan harga produk yang beredar di pasar sebagai bahan perbandingan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_3, 'name' => '3.2 Data volume produk yang laku pada periode waktu tertentu untuk setiap jenis produk dikumpulkan.', 'description' => 'Menghimpun data penjualan produk dalam periode tertentu untuk mengetahui tingkat permintaan pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_3, 'name' => '3.3 Data produsen atau supplier setiap jenis produk dikumpulkan.', 'description' => 'Mengidentifikasi produsen atau pemasok produk yang ada di pasar sebagai referensi rantai pasok.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_3, 'name' => '3.4 Data biaya logistik (transportasi dan pergudangan) setiap jenis produk dari setiap produsen/supplier dikumpulkan.', 'description' => 'Mengumpulkan informasi biaya logistik untuk mengetahui struktur biaya distribusi produk.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_1_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_1,
            'title' => '4. Melakukan analisis data hasil survey pasar',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_1_4, 'name' => '4.1 Data hasil survey pasar dikompilasi sesuai dengan tujuan survey.', 'description' => 'Mengelompokkan dan menyusun data hasil survei secara sistematis sesuai tujuan yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_4, 'name' => '4.2 Data hasil survey pasar dianalisis.', 'description' => 'Menganalisis data survei untuk menemukan pola, peluang pasar, dan potensi produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_1_4, 'name' => '4.3 Urutan jenis produk dibuat berdasarkan jenis produk yang paling marketable.', 'description' => 'Menyusun peringkat produk berdasarkan tingkat keterjualan dan daya tarik pasar untuk mendukung pengambilan keputusan bisnis.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_2 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.002.01',
            'unit_title' => 'Melakukan studi proses produksi suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_2_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_2,
            'title' => '1. Menyiapkan pelaksanaan studi proses produksi suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_2_1, 'name' => '1.1 Metoda pengumpulan informasi tentang proses produksi suatu produk tertentu dijelaskan.', 'description' => 'Menjelaskan metode yang digunakan untuk memperoleh data dan informasi terkait tahapan dan alur proses produksi suatu produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_1, 'name' => '1.2 Teknik berkomunikasi dengan sumber data dijelaskan.', 'description' => 'Menjelaskan cara berkomunikasi yang efektif dengan narasumber seperti operator, teknisi, atau manajemen produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_1, 'name' => '1.3 Alat untuk pengumpulan data dan informasi disiapkan.', 'description' => 'Menyiapkan instrumen pengumpulan data seperti formulir, checklist, alat ukur, atau perangkat dokumentasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_2_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_2,
            'title' => '2. Melaksanakan studi bahan baku, bahan pembantu, produk antara, produk jadi, dan limbah B3',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_2_2, 'name' => '2.1 Bahan baku dan bahan pembantu untuk proses produksi produk tertentu diidentifikasi.', 'description' => 'Mengidentifikasi jenis, spesifikasi, dan fungsi bahan baku serta bahan pembantu dalam proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_2, 'name' => '2.2 Ketersediaan (dan kesinambungan) bahan baku dan bahan pembantu diidentifikasi berdasarkan informasi dari produsen/supplier.', 'description' => 'Menganalisis ketersediaan dan keberlanjutan pasokan bahan baku dan bahan pembantu dari pemasok.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_2, 'name' => '2.3 Tata cara penanganan bahan baku dan bahan pembantu dijelaskan.', 'description' => 'Menjelaskan prosedur penanganan, penyimpanan, dan penggunaan bahan baku serta bahan pembantu sesuai standar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_2, 'name' => '2.4 Tata cara penanganan produk antara dan produk akhir dijelaskan.', 'description' => 'Menjelaskan proses penanganan produk setengah jadi dan produk jadi untuk menjaga mutu dan keamanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_2, 'name' => '2.5 Tata cara penanganan limbah B3 dijelaskan.', 'description' => 'Menjelaskan prosedur pengelolaan dan pembuangan limbah B3 sesuai dengan ketentuan keselamatan dan lingkungan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_2_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_2,
            'title' => '3. Melaksanakan studi proses produksi suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_2_3, 'name' => '3.1 Mesin dan peralatan yang digunakan dan tahapan proses produksi dijelaskan.', 'description' => 'Menjelaskan jenis mesin, peralatan, serta urutan tahapan proses produksi secara sistematis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_3, 'name' => '3.2 Tata cara pengoperasian mesin dan peralatan didemonstrasikan.', 'description' => 'Menunjukkan cara pengoperasian mesin dan peralatan produksi sesuai prosedur kerja.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_3, 'name' => '3.3 Tata cara perawatan mesin dan peralatan didemonstrasikan.', 'description' => 'Menunjukkan langkah-langkah perawatan rutin untuk menjaga kinerja dan umur mesin.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_3, 'name' => '3.4 Efisiensi waktu proses produksi dijelaskan.', 'description' => 'Menjelaskan tingkat efisiensi waktu produksi dan faktor-faktor yang memengaruhinya.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_2_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_2,
            'title' => '4. Menganalisa hasil studi',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_2_4, 'name' => '4.1 Urutan jenis produk dibuat berdasarkan ketersediaan bahan baku dan bahan pembantu.', 'description' => 'Menyusun prioritas jenis produk berdasarkan ketersediaan dan kesinambungan bahan baku serta bahan pembantu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_4, 'name' => '4.2 Urutan jenis produk dibuat berdasarkan kemudahan penanganan bahan baku, produk antara, dan produk akhir.', 'description' => 'Menentukan urutan produk dengan mempertimbangkan kemudahan proses penanganan material dan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_2_4, 'name' => '4.3 Urutan jenis produk dibuat berdasarkan tingkat kesulitan proses produksinya.', 'description' => 'Menyusun prioritas produk berdasarkan kompleksitas dan tingkat kesulitan proses produksinya.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_3 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.004.01',
            'unit_title' => 'Melakukan survey sumber bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_3_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_3,
            'title' => '1. Menyiapkan survey sumber bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_3_1, 'name' => '1.1 Bahan baku dan bahan pembantu yang diperlukan diidentifikasi.', 'description' => 'Mengidentifikasi jenis, spesifikasi, dan jumlah bahan baku serta bahan pembantu yang dibutuhkan dalam proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_3_1, 'name' => '1.2 Produsen/supplier bahan baku dan bahan pembantu diidentifikasi.', 'description' => 'Mengidentifikasi pihak produsen atau pemasok yang menyediakan bahan baku dan bahan pembantu sesuai kebutuhan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_3_1, 'name' => '1.3 Lokasi produsen/supplier diidentifikasi.', 'description' => 'Menentukan lokasi geografis produsen atau supplier untuk keperluan analisis distribusi dan logistik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_3_1, 'name' => '1.4 Alat untuk pengumpulan data dalam pelaksanaan survey sumber bahan baku dan bahan pembantu disiapkan.', 'description' => 'Menyiapkan instrumen survei seperti formulir, checklist, alat dokumentasi, dan media pencatatan data.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_3_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_3,
            'title' => '2. Melakukan survey sumber bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_3_2, 'name' => '2.1 Data setiap produsen/supplier bahan baku dan bahan pembantu dikumpulkan.', 'description' => 'Mengumpulkan data lengkap produsen/supplier meliputi identitas, kapasitas, dan sistem pasokan bahan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_3_2, 'name' => '2.2 Harga dan ketersediaan bahan baku dan bahan pembantu pada setiap produsen/supplier dicatat.', 'description' => 'Mencatat informasi harga, volume ketersediaan, serta kontinuitas pasokan dari masing-masing supplier.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_3_2, 'name' => '2.3 Biaya logistik (transportasi dan pergudangan) bahan baku dan bahan pembantu dari setiap produsen/supplier dicatat.', 'description' => 'Menghitung dan mencatat biaya distribusi dan penyimpanan sebagai dasar analisis total biaya pengadaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_3_2, 'name' => '2.4 Urutan sumber bahan baku dan bahan pembantu disusun berdasarkan ketersediaan, harga dan biaya logistiknya.', 'description' => 'Menyusun prioritas supplier berdasarkan pertimbangan ketersediaan, harga paling efisien, dan biaya logistik terendah.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_4 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.005.01',
            'unit_title' => 'Melakukan analisis harga pulang pokok',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_4_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_4,
            'title' => '1. Menyiapkan analisis harga pokok',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_4_1, 'name' => '1.1 Pengetahuan dasar tentang tata hitung ongkos dijelaskan', 'description' => 'Memahami konsep dasar perhitungan biaya produksi seperti biaya tetap, biaya variabel, dan biaya total.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_1, 'name' => '1.2 Data kapasitas terpasang sarana produksi disiapkan', 'description' => 'Menyiapkan informasi mengenai kemampuan maksimal mesin atau fasilitas produksi dalam periode tertentu.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_1, 'name' => '1.3 Data kebutuhan tenaga kerja langsung dan tidak langsung serta gajinya per satuan waktu tertentu disiapkan', 'description' => 'Mengidentifikasi jumlah tenaga kerja yang terlibat langsung maupun tidak langsung dalam produksi beserta biaya gajinya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_1, 'name' => '1.4 Data kebutuhan bahan baku dan bahan pembantu dan harganya per satuan waktu tertentu disiapkan', 'description' => 'Menghitung kebutuhan bahan utama dan bahan pendukung produksi serta biaya pembeliannya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_1, 'name' => '1.5 Data kebutuhan utilitas langsung dan tidak langsung (listrik, air, uap, dll) serta harganya per satuan waktu tertentu disiapkan', 'description' => 'Mencatat penggunaan utilitas produksi dan biaya yang timbul selama proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_1, 'name' => '1.6 Bunga bank dan nilai penyusutan dihitung', 'description' => 'Menghitung beban bunga pinjaman dan penyusutan aset sebagai bagian dari biaya produksi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_4_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_4,
            'title' => '2. Melakukan analisis harga pokok',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_4_2, 'name' => '2.1 Biaya tetap dihitung setiap satuan waktu tertentu', 'description' => 'Menghitung biaya yang jumlahnya tetap meskipun volume produksi berubah, seperti sewa dan gaji tetap.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_2, 'name' => '2.2 Biaya tidak tetap dihitung untuk tiap unit produk', 'description' => 'Menghitung biaya variabel yang berubah sesuai jumlah unit produk yang dihasilkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_2, 'name' => '2.3 Harga jual produk tiap unit ditentukan berdasarkan biaya tetap dan biaya tidak tetap', 'description' => 'Menentukan harga jual dengan mempertimbangkan total biaya produksi dan margin keuntungan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_4_2, 'name' => '2.4 Harga pokok dianalisis', 'description' => 'Menganalisis hasil perhitungan harga pokok sebagai dasar pengambilan keputusan usaha.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_5 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.006.01',
            'unit_title' => 'Menentukan jenis produk yang akan diusahakan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_5_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_5,
            'title' => '1. Menyiapkan penentuan jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_5_1, 'name' => '1.1 Pengetahuan dasar tentang survey pasar dijelaskan.', 'description' => 'Menjelaskan konsep dasar survei pasar, tujuan survei, jenis data pasar, serta perannya dalam pengambilan keputusan penentuan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_5_1, 'name' => '1.2 Pengetahuan dasar tentang tata hitung ongkos dijelaskan.', 'description' => 'Menjelaskan prinsip perhitungan biaya produksi, biaya tetap dan variabel, serta kaitannya dengan penentuan harga produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_5_1, 'name' => '1.3 Pengetahuan dasar tentang pengolahan dan analisis data dijelaskan.', 'description' => 'Menjelaskan cara mengolah data survei dan data produksi serta melakukan analisis sederhana untuk mendukung penentuan jenis produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_5_1, 'name' => '1.4 Data hasil survey pasar, hasil studi proses produksi suatu produk, hasil perhitungan biaya investasi, hasil survey bahan baku, dan hasil analisis harga pokok disiapkan.', 'description' => 'Menyiapkan dan mengompilasi seluruh data pendukung yang dibutuhkan sebagai dasar analisis dalam menentukan jenis produk.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_5_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_5,
            'title' => '2. Menentukan jenis produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_5_2, 'name' => '2.1 Pengelompokan setiap jenis produk dibuat berdasarkan hasil survey pasar, hasil studi proses produksi, hasil perhitungan biaya investasi, hasil survey bahan baku, dan hasil analisis harga pokok.', 'description' => 'Mengelompokkan produk berdasarkan karakteristik pasar, proses produksi, kebutuhan investasi, ketersediaan bahan baku, dan biaya produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_5_2, 'name' => '2.2 Hasil pengelompokan dianalisis.', 'description' => 'Menganalisis setiap kelompok produk untuk menilai potensi pasar, kelayakan produksi, dan tingkat keuntungan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_5_2, 'name' => '2.3 Jenis produk yang akan diusahakan ditentukan berdasarkan hasil analisis.', 'description' => 'Menetapkan jenis produk yang paling layak dan menguntungkan untuk diusahakan berdasarkan hasil analisis yang telah dilakukan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_6 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.009.01',
            'unit_title' => 'Melakukan pengadaan mesin dan peralatan yang dibutuhkan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_6_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_6,
            'title' => '1. Menyiapkan pengadaan mesin dan peralatan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_6_1, 'name' => '1.1 Daftar mesin dan peralatan yang dibutuhkan disiapkan.', 'description' => 'Menyusun daftar jenis, jumlah, dan fungsi mesin serta peralatan yang diperlukan sesuai kebutuhan proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_6_1, 'name' => '1.2 Data produsen/supplier mesin dan peralatan, serta spesifikasi dan harga setiap mesin dan peralatan dikumpulkan.', 'description' => 'Mengumpulkan informasi lengkap terkait produsen atau supplier, spesifikasi teknis, dan harga mesin serta peralatan yang ditawarkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_6_1, 'name' => '1.3 Data kemudahan dan fasilitas yang ditawarkan oleh setiap produsen/supplier dikumpulkan.', 'description' => 'Mengidentifikasi layanan tambahan seperti garansi, pelatihan, layanan purna jual, dan kemudahan pembayaran dari setiap supplier.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_6_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_6,
            'title' => '2. Melakukan pengadaan mesin dan peralatan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_6_2, 'name' => '2.1 Spesifikasi dan harga mesin dan peralatan, serta kemudahan dan fasilitas dari setiap produsen/supplier dianalisis.', 'description' => 'Menganalisis kesesuaian spesifikasi, harga, serta fasilitas pendukung untuk menentukan pilihan terbaik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_6_2, 'name' => '2.2 Produsen/supplier masing-masing mesin dan peralatan dipilih berdasarkan hasil analisis.', 'description' => 'Menetapkan produsen atau supplier mesin dan peralatan yang paling memenuhi kebutuhan teknis dan efisiensi biaya.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_6_2, 'name' => '2.3 Pengadaan mesin dan peralatan yang akan digunakan dilakukan sesuai prosedur yang disepakati dengan produsen/supplier.', 'description' => 'Melaksanakan proses pembelian atau pengadaan mesin dan peralatan sesuai prosedur administrasi dan kesepakatan yang telah ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_7 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.018.01',
            'unit_title' => 'Melakukan pengadaan bahan baku dan bahan Pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_7_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_7,
            'title' => '1. Menyiapkan pengadaan bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_7_1, 'name' => '1.1 Daftar dan volume bahan baku dan bahan pembantu yang dibutuhkan disiapkan sesuai prosedur.', 'description' => 'Menyusun daftar jenis serta jumlah bahan baku dan bahan pembantu yang diperlukan berdasarkan kebutuhan produksi dan standar operasional.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_7_1, 'name' => '1.2 Data produsen/supplier bahan baku dan bahan pembantu, serta harga bahan baku dan bahan pembantu dikumpulkan.', 'description' => 'Mengumpulkan informasi supplier termasuk harga bahan baku dan bahan pembantu sebagai dasar perbandingan dan analisis pengadaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_7_1, 'name' => '1.3 Data ketersediaan bahan baku dan bahan pembantu dan waktu serta tempat pengiriman yang ditawarkan oleh setiap produsen/supplier dikumpulkan.', 'description' => 'Mengidentifikasi ketersediaan bahan, jadwal pengiriman, serta lokasi pengiriman dari masing-masing supplier.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_7_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_7,
            'title' => '2. Melakukan pengadaan bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_7_2, 'name' => '2.1 Spesifikasi dan harga bahan baku dan bahan pembantu, serta ketersediaan dan pengiriman dari setiap produsen/supplier dianalisis.', 'description' => 'Menganalisis kesesuaian spesifikasi, harga, kontinuitas pasokan, dan sistem pengiriman untuk memilih supplier terbaik.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_7_2, 'name' => '2.2 Produsen/supplier bahan baku dan bahan pembantu dipilih berdasarkan hasil analisis.', 'description' => 'Menetapkan supplier bahan baku dan bahan pembantu berdasarkan hasil evaluasi teknis, harga, dan ketersediaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_7_2, 'name' => '2.3 Pengadaan bahan baku dan bahan pembantu dilakukan sesuai prosedur.', 'description' => 'Melaksanakan proses pemesanan dan pembelian bahan baku serta bahan pembantu sesuai prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_7_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_7,
            'title' => '3. Melaporkan pelaksanaan pengadaan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_7_3, 'name' => '3.1 Pengadaan bahan baku dan bahan pembantu dilaporkan sesuai prosedur.', 'description' => 'Menyusun laporan pelaksanaan pengadaan bahan baku dan bahan pembantu sesuai format dan alur pelaporan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_7_3, 'name' => '3.2 Dokumen pengadaan didokumentasikan.', 'description' => 'Mengarsipkan seluruh dokumen pengadaan sebagai bukti administrasi dan bahan evaluasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_8 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.019.01',
            'unit_title' => 'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_8_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_8,
            'title' => '1. Menyiapkan pengaturan penyimpanan bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_8_1, 'name' => '1.1 Pengetahuan tentang administrasi pergudangan dijelaskan', 'description' => 'Menjelaskan konsep dasar administrasi pergudangan, termasuk pencatatan, pengendalian stok, dan pengelolaan ruang simpan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_1, 'name' => '1.2 Daftar dan volume bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan', 'description' => 'Menyusun daftar jenis serta jumlah bahan dan produk sebagai dasar perencanaan penyimpanan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_1, 'name' => '1.3 Data teknis dan prosedur penyimpanan masing-masing bahan baku, bahan pembantu, produk antara, dan produk akhir dikumpulkan', 'description' => 'Mengumpulkan informasi teknis seperti suhu, kelembaban, masa simpan, dan cara penanganan setiap jenis bahan dan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_1, 'name' => '1.4 Metode pengambilan barang ditentukan berdasarkan FIFO (first in first out) atau LIFO (last in first out)', 'description' => 'Menentukan metode pengambilan barang agar mutu terjaga dan risiko kedaluwarsa dapat diminimalkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_1, 'name' => '1.5 Tempat dan peralatan penyimpanan untuk masing-masing bahan disiapkan', 'description' => 'Menyiapkan ruang, rak, kontainer, dan peralatan pendukung penyimpanan sesuai karakteristik bahan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_8_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_8,
            'title' => '2. Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_8_2, 'name' => '2.1 Rencana penyimpanan bahan baku dan bahan pembantu diatur berdasarkan jenis dan spesifikasi barang', 'description' => 'Mengatur tata letak penyimpanan sesuai jenis, ukuran, dan karakteristik bahan untuk memudahkan pengendalian stok.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_2, 'name' => '2.2 Label nama bahan baku, bahan pembantu, produk antara, dan produk akhir dipasang pada masing-masing tempat penyimpanan', 'description' => 'Memberi identitas yang jelas pada setiap lokasi penyimpanan untuk mencegah kesalahan pengambilan barang.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_2, 'name' => '2.3 Administrasi penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir disiapkan', 'description' => 'Menyiapkan dokumen dan catatan penyimpanan seperti kartu stok atau sistem pencatatan digital.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_8_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_8,
            'title' => '3. Melaporkan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara, dan produk akhir',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_8_3, 'name' => '3.1 Persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir dilaporkan sesuai prosedur', 'description' => 'Melaporkan jumlah dan posisi persediaan secara berkala sesuai dengan prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_8_3, 'name' => '3.2 Informasi persediaan dan lokasi bahan baku, bahan pembantu, produk antara, dan produk akhir didokumentasikan', 'description' => 'Mendokumentasikan data persediaan dan lokasi penyimpanan sebagai arsip dan bahan evaluasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_9 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.021.01',
            'unit_title' => 'Memeriksa jenis, spesifikasi, dan kondisi bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_9_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_9,
            'title' => '1. Menyiapkan pemeriksaan jenis, spesifikasi, dan kondisi bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_9_1, 'name' => '1.1 Pengetahuan tentang karakteristik bahan baku dan bahan pembantu dijelaskan.', 'description' => 'Menjelaskan sifat, fungsi, dan karakteristik bahan baku serta bahan pembantu yang digunakan dalam proses produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_1, 'name' => '1.2 Pengetahuan tentang pemeriksaan karakteristik bahan baku dan bahan pembantu dijelaskan.', 'description' => 'Menjelaskan metode dan teknik pemeriksaan karakteristik bahan baku dan bahan pembantu sesuai standar yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_1, 'name' => '1.3 Standar teknis dan standar pemeriksaan bahan baku dan bahan pembantu disiapkan sesuai prosedur.', 'description' => 'Menyediakan dokumen standar teknis dan standar pemeriksaan sebagai acuan pelaksanaan pemeriksaan bahan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_1, 'name' => '1.4 Peralatan pemeriksaan disiapkan sesuai prosedur.', 'description' => 'Menyiapkan alat dan instrumen pemeriksaan agar siap digunakan dan berfungsi sesuai ketentuan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_1, 'name' => '1.5 Bahan baku dan bahan pembantu yang akan diperiksa disiapkan sesuai prosedur.', 'description' => 'Menyiapkan bahan baku dan bahan pembantu untuk proses pemeriksaan sesuai alur dan ketentuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_9_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_9,
            'title' => '2. Melakukan pemeriksaan jenis, spesifikasi, dan kondisi bahan baku dan bahan pembantu',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_9_2, 'name' => '2.1 Pemeriksaan dilakukan berdasarkan aturan perusahaan (contoh atau populasi).', 'description' => 'Melaksanakan pemeriksaan bahan baku dan bahan pembantu menggunakan metode sampling atau pemeriksaan populasi sesuai aturan perusahaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_2, 'name' => '2.2 Prosedur pemeriksaan dilakukan berdasarkan standar pemeriksaan.', 'description' => 'Melakukan pemeriksaan dengan mengikuti tahapan dan ketentuan yang tercantum dalam standar pemeriksaan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_2, 'name' => '2.3 Hasil pemeriksaan dicatat sesuai prosedur.', 'description' => 'Mencatat hasil pemeriksaan secara lengkap dan akurat pada formulir atau sistem yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_9_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_9,
            'title' => '3. Melaporkan hasil pemeriksaan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_9_3, 'name' => '3.1 Hasil pemeriksaan dilaporkan.', 'description' => 'Menyusun dan menyampaikan laporan hasil pemeriksaan kepada pihak yang berwenang sesuai prosedur organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_9_3, 'name' => '3.2 Hasil pemeriksaan didokumentasikan.', 'description' => 'Mengarsipkan dokumen hasil pemeriksaan sebagai bukti administrasi dan bahan evaluasi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_10 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.023.01',
            'unit_title' => 'Melakukan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_10_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_10,
            'title' => '1. Menyiapkan pelaksanaan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_10_1, 'name' => '1.1 Pengetahuan tentang produk dijelaskan', 'description' => 'Menjelaskan karakteristik, keunggulan, dan manfaat produk sebagai dasar kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_10_1, 'name' => '1.2 Target dan segmen pelanggan ditentukan berdasarkan jenis produk', 'description' => 'Menentukan sasaran promosi berdasarkan segmentasi pasar yang sesuai dengan produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_10_1, 'name' => '1.3 Wilayah dan lokasi pemasaran ditentukan', 'description' => 'Menetapkan area dan lokasi pemasaran yang paling potensial untuk kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_10_1, 'name' => '1.4 Metode dan prosedur promosi disiapkan sesuai prosedur', 'description' => 'Menyiapkan metode promosi (online/offline) serta prosedur pelaksanaannya sesuai ketentuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_10_1, 'name' => '1.5 Personil dan peralatan promosi disiapkan sesuai prosedur', 'description' => 'Menyiapkan sumber daya manusia dan peralatan yang dibutuhkan untuk kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_10_1, 'name' => '1.6 Biaya promosi dihitung berdasarkan rencana kegiatan', 'description' => 'Menghitung anggaran promosi sesuai dengan rencana dan skala kegiatan yang akan dilaksanakan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_10_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_10,
            'title' => '2. Melakukan promosi produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_10_2, 'name' => '2.1 Promosi produk dilakukan sesuai rencana', 'description' => 'Melaksanakan kegiatan promosi sesuai jadwal, metode, dan anggaran yang telah ditentukan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_10_2, 'name' => '2.2 Masukan dari pelanggan, pengecer, dan distributor dikumpulkan', 'description' => 'Mengumpulkan umpan balik dari berbagai pihak sebagai bahan evaluasi kegiatan promosi.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_11 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.024.01',
            'unit_title' => 'Melakukan survey atas pedagang eceran suatu Produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_11_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_11,
            'title' => '1. Menyiapkan pelaksanaan survey atas pedagang eceran suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_11_1, 'name' => '1.1 Pengetahuan dasar tentang riset pasar dijelaskan.', 'description' => 'Menjelaskan konsep dasar riset pasar sebagai landasan pelaksanaan survey pedagang eceran.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_1, 'name' => '1.2 Jenis produk dan masalah yang akan diteliti ditentukan.', 'description' => 'Menentukan produk yang disurvey serta permasalahan pasar yang ingin diketahui melalui survey.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_1, 'name' => '1.3 Tujuan survey ditentukan sebagai dasar pelaksanaan survey.', 'description' => 'Merumuskan tujuan survey secara jelas agar pelaksanaan dan hasil survey terarah.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_11_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_11,
            'title' => '2. Menyusun rencana survey',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_11_2, 'name' => '2.1 Lokasi dan sumber data ditentukan sesuai rencana pemasaran produk.', 'description' => 'Menetapkan area survey dan sumber data yang relevan berdasarkan rencana pemasaran produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_2, 'name' => '2.2 Metoda survey, jumlah responden dan jumlah surveyor ditentukan.', 'description' => 'Menentukan metode pengumpulan data, jumlah responden, dan tenaga surveyor sesuai kebutuhan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_2, 'name' => '2.3 Biaya pelaksanaan survey dihitung berdasarkan rencana kegiatan.', 'description' => 'Menghitung anggaran biaya survey agar pelaksanaan sesuai dengan rencana dan kemampuan organisasi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_2, 'name' => '2.4 Alat untuk pengumpulan data dalam pelaksanaan survey disiapkan.', 'description' => 'Menyiapkan instrumen survey seperti kuesioner, formulir, atau alat digital pendukung.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_11_3 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_11,
            'title' => '3. Melaksanakan survey atas pedagang eceran suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_11_3, 'name' => '3.1 Pengecer yang akan disurvey diidentifikasi.', 'description' => 'Menentukan dan mendata pedagang eceran yang menjadi responden survey.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_3, 'name' => '3.2 Harga produk dan volume produk yang laku pada periode waktu tertentu pada setiap pengecer dikumpulkan sesuai prosedur.', 'description' => 'Mengumpulkan data harga dan volume penjualan produk dari masing-masing pengecer secara sistematis.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_3, 'name' => '3.3 Spesifikasi produk dan produsen dari produk yang paling laku pada setiap pengecer diidentifikasi.', 'description' => 'Mengidentifikasi karakteristik produk unggulan serta produsen dari produk yang paling diminati pasar.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_11_4 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_11,
            'title' => '4. Melakukan analisis data hasil survey atas pedagang eceran suatu produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_11_4, 'name' => '4.1 Data hasil survey dikompilasi sesuai dengan tujuan survey.', 'description' => 'Mengelompokkan dan menyusun data survey agar siap dianalisis sesuai tujuan yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_4, 'name' => '4.2 Data hasil survey dianalisis sesuai prosedur.', 'description' => 'Melakukan analisis data menggunakan metode yang sesuai untuk memperoleh temuan yang valid.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_4, 'name' => '4.3 Hasil analisis data dievaluasi sesuai prosedur.', 'description' => 'Menilai hasil analisis untuk memastikan kesesuaian dengan tujuan dan kualitas data.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_11_4, 'name' => '4.4 Hasil akhir survey dilaporkan kepada pihak yang berkepentingan.', 'description' => 'Menyusun dan menyampaikan laporan hasil survey kepada pihak terkait sebagai dasar pengambilan keputusan.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $unit10_12 = DB::table('competency_units')->insertGetId([
            'scheme_id' => $scheme10,
            'unit_code' => 'M.741000.025.01',
            'unit_title' => 'Melakukan pemasaran produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $elem10_12_1 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_12,
            'title' => '1. Menyiapkan pelaksanaan pemasaran produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_12_1, 'name' => '1.1 Pengetahuan tentang pemasaran dijelaskan.', 'description' => 'Menjelaskan konsep dasar pemasaran sebagai landasan pelaksanaan kegiatan pemasaran produk.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_1, 'name' => '1.2 Spesifikasi produk, volume, dan harganya diidentifikasi.', 'description' => 'Mengidentifikasi karakteristik produk, jumlah yang dipasarkan, serta harga yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_1, 'name' => '1.3 Target penjualan ditentukan sesuai rencana produksi.', 'description' => 'Menetapkan target penjualan berdasarkan kapasitas dan rencana produksi.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_1, 'name' => '1.4 Wilayah dan lokasi pemasaran ditentukan sesuai rencana pemasaran produk.', 'description' => 'Menentukan area pemasaran dan titik distribusi sesuai strategi pemasaran yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_1, 'name' => '1.5 Biaya pengiriman barang dihitung dari lokasi produksi ke setiap lokasi pemasaran.', 'description' => 'Menghitung biaya logistik agar harga jual dan margin keuntungan dapat ditetapkan secara akurat.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $elem10_12_2 = DB::table('competency_elements')->insertGetId([
            'competency_unit_id' => $unit10_12,
            'title' => '2. Melakukan pemasaran produk',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('performance_criteria')->insert([
            ['element_id' => $elem10_12_2, 'name' => '2.1 Calon distributor dan pengecer diidentifikasi sesuai prosedur.', 'description' => 'Mengidentifikasi pihak distributor dan pengecer potensial sesuai ketentuan yang berlaku.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_2, 'name' => '2.2 Distributor dan pengecer ditentukan berdasarkan hasil survey.', 'description' => 'Menetapkan mitra distribusi berdasarkan hasil analisis dan survey pasar.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_2, 'name' => '2.3 Cara pengiriman barang dan pembayaran ditentukan sesuai kesepakatan.', 'description' => 'Menentukan sistem distribusi dan mekanisme pembayaran berdasarkan kesepakatan bersama.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_2, 'name' => '2.4 Distribusi produk kepada distributor dan pengecer dilakukan sesuai prosedur.', 'description' => 'Melaksanakan pendistribusian produk sesuai standar operasional yang ditetapkan.', 'created_at' => $now, 'updated_at' => $now],
            ['element_id' => $elem10_12_2, 'name' => '2.5 Pemasaran produk langsung ke pelanggan dilakukan sesuai prosedur.', 'description' => 'Melakukan penjualan langsung kepada konsumen akhir sesuai ketentuan dan prosedur pemasaran.', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('  [+] Skema 10: JENJANG IV BIDANG KEWIRAUSAHAAN INDUSTRI');

        $this->command->info('');
        $this->command->info('  SchemeSeeder selesai — 10 skema berhasil di-seed.');
    }
}