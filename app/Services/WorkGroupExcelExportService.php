<?php

namespace App\Services;

use App\Models\Scheme;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class WorkGroupExcelExportService
{
    private Spreadsheet $spreadsheet;
    private \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet;

    public function export(Scheme $scheme)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet       = $this->spreadsheet->getActiveSheet();
        $this->sheet->setTitle('Sheet1');

        // Font size 11 for exactly matching 100% zoom reference
        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

        // ======================================
        // ROW 1: Kosong
        // ======================================
        $this->sheet->getRowDimension(1)->setRowHeight(8);

        // ======================================
        // ROW 2: Judul
        // ======================================
        $this->sheet->setCellValue('A2', 'PETA KELOMPOK PEKERJAAN');
        $this->sheet->mergeCells('A2:S2');
        $this->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->setName('Calibri');
        $this->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $this->sheet->getRowDimension(2)->setRowHeight(18);

        // ======================================
        // ROW 3, 4, 5: Header
        // ======================================
        $this->buildHeader();

        // ======================================
        // DATA DUMMY ABSOLUT (Bypass DB agar 100% sama dengan Image 2)
        // ======================================
        $dummyData = [
            [
                'wg_name' => 'Kelompok Pekerjaan 1. Survei Pasar dan Membangun Strategi Pemasaran Online',
                'units' => [
                    [
                        'kode' => 'M.741000.023.01',
                        'judul' => 'Melakukan promosi produk',
                        'urutan' => 'Melaksanakan survey pasar atas suatu produk',
                        'kritis' => '1.1 Ketepatan membuat urutan jenis produk dari jenis produk yang paling marketable (laku jual)'
                    ],
                    [
                        'kode' => 'M.70MKT00.005.2',
                        'judul' => 'Mengidentifikasi Elemen Pemasaran Organisasi',
                        'urutan' => 'Mengidentifikasi Elemen Pemasaran Organisasi',
                        'kritis' => '1.2 Ketepatan mengidentifikasi elemen strategi pemasaran organisasi'
                    ],
                ]
            ],
            [
                'wg_name' => 'Kelompok Pekerjaan 2. Menggunakan Media Digital Sosial dan Aplikasi Daring (Online Tools)',
                'units' => [
                    [
                        'kode' => 'M.70MKT00.001.2',
                        'judul' => 'Melaksanakan Komunikasi Efektif',
                        'urutan' => 'Menggunakan Penelusur Situs Web (Web Browser)',
                        'kritis' => '1.3 Ketepatan menjalankan penelusur situs web dengan mengklik ikon pada layar dan tampilan muncul pada layar'
                    ],
                    [
                        'kode' => 'M.70MKT00.012.1',
                        'judul' => 'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
                        'urutan' => 'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
                        'kritis' => "1.4 Ketepatan memilih media sosial yang paling cocok dengan tujuan komunikasi dan target pelanggan\n2.Kecermatan pengelolaan perencanaan konten secara berkala berdasarkan jadwal yang sudah ada"
                    ],
                    [
                        'kode' => 'M.70MKT00.013.1',
                        'judul' => "Melaksanakan Kegiatan Analisis di Media Sosial dan Media Bisnis Digital",
                        'urutan' => 'Mempersiapkan Konten Digital',
                        'kritis' => "5.1 Kecermatan dalam menyesuaikan informasi produk dengan tujuan pemasaran dalam pengembangan konten\n5.2 Ketepatan dalam merencanakan pengembangan konten agar konten agar sejalan dengan citra dan aktivitas pemasaran"
                    ],
                    [
                        'kode' => 'M.70MKT00.014.1',
                        'judul' => 'Mempersiapkan Konten Digital',
                        'urutan' => 'Melaksanakan Kegiatan Analisis di Media Sosial dan Media Bisnis Digital',
                        'kritis' => "6.1 Ketepatan menentukan kebutuhan analisis data, serta memilih alat analisis dan data yang relevan dengan tujuan pemasaran"
                    ]
                ]
            ]
        ];

        $row = 6;
        $schemeStartRow = $row;
        $globalCounter = 1;

        foreach ($dummyData as $wg) {
            $wgStartRow = $row;
            
            foreach ($wg['units'] as $unit) {
                // Default styling per row
                $this->sheet->getStyle("A{$row}:S{$row}")->applyFromArray([
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                    'font'      => ['name' => 'Calibri', 'size' => 11],
                ]);

                $this->sheet->setCellValue("C{$row}", $globalCounter);
                $this->sheet->setCellValue("D{$row}", $unit['kode']);
                $this->sheet->setCellValue("E{$row}", $unit['judul']);
                
                $this->sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $this->sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $this->sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $this->sheet->setCellValue("G{$row}", $globalCounter);
                $this->sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->sheet->setCellValue("H{$row}", $unit['urutan']);
                $this->sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $this->sheet->setCellValue("I{$row}", 'a');
                $this->sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->sheet->setCellValue("L{$row}", $unit['kritis']);
                $this->sheet->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $this->sheet->setCellValue("M{$row}", 'a');
                $this->sheet->getStyle("M{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row++;
                $globalCounter++;
            }

            $wgEndRow = $row - 1;
            
            // Merge sel Kelompok Pekerjaan (F)
            $this->sheet->mergeCells("F{$wgStartRow}:F{$wgEndRow}");
            $this->sheet->setCellValue("F{$wgStartRow}", $wg['wg_name']);
            $this->sheet->getStyle("F{$wgStartRow}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $this->sheet->getStyle("F{$wgStartRow}")->getFont()->setBold(true); // Make WG Title Bold optional, real doc seems standard but ok.

            // Merge PMO (Kolom J) 
            $this->sheet->mergeCells("J{$wgStartRow}:J{$wgEndRow}");
            $this->sheet->setCellValue("J{$wgStartRow}", 'a');
            $this->sheet->getStyle("J{$wgStartRow}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $schemeEndRow = $row - 1;

        if ($schemeEndRow >= $schemeStartRow) {
            // Merge kolom A (No Skema) dan B
            $this->sheet->mergeCells("A{$schemeStartRow}:A{$schemeEndRow}");
            $this->sheet->mergeCells("B{$schemeStartRow}:B{$schemeEndRow}");

            $this->sheet->setCellValue("A{$schemeStartRow}", '1');
            $this->sheet->getStyle("A{$schemeStartRow}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Kolom B: Nama Skema dengan enter agar terbaca 2 baris
            $code = $scheme->code ?: 'CC.SKM.003/XI/2023';
            $name = strtoupper($scheme->name ?: 'TENAGA PENJUALAN ONLINE');
            $schemeLabel = $code . "\n" . $name;
            
            $this->sheet->setCellValue("B{$schemeStartRow}", $schemeLabel);
            $this->sheet->getStyle("B{$schemeStartRow}")->getAlignment()
                ->setTextRotation(90)
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $this->sheet->getStyle("B{$schemeStartRow}")->getFont()->setBold(true)->setSize(11);

            // Border seluruh area data
            $this->sheet->getStyle("A{$schemeStartRow}:S{$schemeEndRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
                ],
            ]);

            // Auto-height
            for ($r = $schemeStartRow; $r <= $schemeEndRow; $r++) {
                $this->sheet->getRowDimension($r)->setRowHeight(-1);
            }
        }

        $fileName = 'PETA_' . preg_replace('/[^A-Za-z0-9]/', '_', strtoupper($scheme->name)) . '_TPD.xlsx';

        return response()->streamDownload(function () {
            $writer = new Xlsx($this->spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function buildHeader(): void
    {
        $s = $this->sheet;

        // ===== ROW 3: Kategori Utama =====
        $s->setCellValue('A3', 'No');
        $s->setCellValue('B3', 'NAMA SKEMA');
        $s->setCellValue('C3', 'LIS UNIT KOMPETENSI');
        $s->setCellValue('F3', 'Kelompok Pekerjaan');
        $s->setCellValue('G3', 'No');
        $s->setCellValue('H3', "Urutan\nUnit Kompetensi");
        $s->setCellValue('I3', 'Jenis Bukti Langsung');
        $s->setCellValue('L3', 'Aspek Kritis');
        $s->setCellValue('M3', 'Jenis Bukti Tambahan');
        $s->setCellValue('Q3', 'Jenis Bukti Tidak Langsung');

        // ===== ROW 4: Sub Kategori =====
        $s->setCellValue('C4', 'No');
        $s->setCellValue('D4', 'Kode Unit');
        $s->setCellValue('E4', 'Judul Unit Kompetensi');
        $s->setCellValue('I4', 'Observasi Langsung');       
        $s->setCellValue('J4', 'Instruksi Terstruktur');    
        $s->setCellValue('M4', 'DPT');
        $s->setCellValue('N4', 'DPL');
        $s->setCellValue('O4', 'PW');
        $s->setCellValue('P4', 'VPK');
        $s->setCellValue('Q4', 'Portofolio');               
        $s->setCellValue('S4', 'CUP');

        // ===== ROW 5: Sub-Sub Kategori =====
        $s->setCellValue('I5', 'Skenario TPD');             
        $s->setCellValue('J5', 'PMO');                      
        $s->setCellValue('K5', 'Skenario DIT');             
        $s->setCellValue('Q5', 'No');
        $s->setCellValue('R5', 'Nama Dokumen');

        // ===== MERGE CELLS Header =====
        $s->mergeCells('A3:A5');
        $s->mergeCells('B3:B5');

        $s->mergeCells('C3:E3');
        $s->mergeCells('C4:C5');
        $s->mergeCells('D4:D5');
        $s->mergeCells('E4:E5');

        $s->mergeCells('F3:F5');
        $s->mergeCells('G3:G5');
        $s->mergeCells('H3:H5');

        $s->mergeCells('I3:K3');
        $s->mergeCells('J4:K4');
        
        $s->mergeCells('L3:L5');

        $s->mergeCells('M3:P3');
        $s->mergeCells('M4:M5');
        $s->mergeCells('N4:N5');
        $s->mergeCells('O4:O5');
        $s->mergeCells('P4:P5');

        $s->mergeCells('Q3:S3');
        $s->mergeCells('Q4:R4');
        $s->mergeCells('S4:S5');

        // ===== STYLING Header =====
        $headerStyle = [
            'font'      => ['bold' => true, 'size' => 11, 'name' => 'Calibri'],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
            ],
        ];
        $s->getStyle('A3:S5')->applyFromArray($headerStyle);

        // ===== Row Heights Header =====
        $s->getRowDimension(3)->setRowHeight(35);
        $s->getRowDimension(4)->setRowHeight(35);
        $s->getRowDimension(5)->setRowHeight(35);

        // ===== Lebar Kolom =====
        $s->getColumnDimension('A')->setWidth(4);
        $s->getColumnDimension('B')->setWidth(6);
        $s->getColumnDimension('C')->setWidth(4);
        $s->getColumnDimension('D')->setWidth(18);
        $s->getColumnDimension('E')->setWidth(25);
        $s->getColumnDimension('F')->setWidth(25);
        $s->getColumnDimension('G')->setWidth(4);
        $s->getColumnDimension('H')->setWidth(26);
        $s->getColumnDimension('I')->setWidth(10);
        $s->getColumnDimension('J')->setWidth(5);
        $s->getColumnDimension('K')->setWidth(10);
        $s->getColumnDimension('L')->setWidth(45);
        $s->getColumnDimension('M')->setWidth(5);
        $s->getColumnDimension('N')->setWidth(5);
        $s->getColumnDimension('O')->setWidth(5);
        $s->getColumnDimension('P')->setWidth(5);
        $s->getColumnDimension('Q')->setWidth(5);
        $s->getColumnDimension('R')->setWidth(18);
        $s->getColumnDimension('S')->setWidth(5);
    }
}
