<?php

namespace App\Exports;

use App\Models\AssessmentRegistration;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AsesiBlankoExport
{
    /**
     * Lebar kolom (dalam karakter Excel).
     */
    protected array $columnWidths = [
        4,    // A: No
        22,   // B: Nama Asesi
        20,   // C: NIK
        16,   // D: Tempat Lahir
        13,   // E: Tanggal Lahir
        10,   // F: Jenis Kelamin
        30,   // G: Tempat Tinggal
        8,    // H: Kode Kota
        8,    // I: Kode Provinsi
        16,   // J: Telp
        30,   // K: Email
        10,   // L: Kode Pendidikan
        10,   // M: Kode Pekerjaan
        12,   // N: Kode Jadwal
        13,   // O: Tanggal Uji
        24,   // P: Nomor Registrasi Asesor
        10,   // Q: Kode Number Anggaran
        10,   // R: Kode Ktment Erian
        6,    // S: K/BK
    ];

    /**
     * Mengembalikan array header kolom blanko asesi sesuai standar.
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'No',
            'NAMA ASESI',
            'NIK',
            'TEMPAT LAHIR',
            "TANGGAL LAHIR\n(dd/mm/yy)",
            "JENIS KELAMIN\n(L/P)",
            'TEMPAT TINGGAL',
            "KODE\nKOTA",
            "KODE\nPROVINSI",
            'TELP',
            'EMAIL',
            "KODE\nPENDIDIKAN",
            "KODE\nPEKERJAAN",
            'KODE JADWAL',
            "TANGGAL UJI\n(hh/bb/yy)",
            'NOMOR REGISTRASI ASESOR',
            "KODE\nSUMBER\nANGGARAN",
            "KODE\nKEMENTRIAN",
            'K/BK',
        ];
    }



    /**
     * Buat dan download file Excel blanko asesi.
     *
     * @param  array  $registrationIds
     * @return StreamedResponse
     */
    public function download(array $registrationIds, string $filename = 'PERMOHONAN BLANKO'): StreamedResponse
    {
        $registrations = AssessmentRegistration::query()
            ->with([
                'candidate:id,name,email,registration_number',
                'candidate.profile',
                'aplData.education:id,title',
                'assessor:id,name,registration_number',
                'scheme:id,name,code',
            ])
            ->whereIn('id', $registrationIds)
            ->orderBy('id')
            ->get();

        $spreadsheet = $this->build($registrations);

        // Sanitasi nama file: hapus karakter ilegal untuk nama file
        $safeFilename = preg_replace('/[\\\\\/:\*\?"<>\|]/', '_', $filename);
        $safeFilename = trim($safeFilename) ?: 'PERMOHONAN BLANKO';
        $outputFilename = $safeFilename . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $outputFilename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Build spreadsheet dari koleksi registrasi.
     *
     * @param  Collection  $registrations
     * @return Spreadsheet
     */
    protected function build(Collection $registrations): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('BLANKO ASESI');

        // =========================================================
        // BARIS 1: Header kolom
        // =========================================================
        $columns     = range('A', 'S'); // A - S (19 kolom)
        $headerRow   = 1;
        $headerStyle = [
            'font'      => ['bold' => true, 'size' => 9],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ];

        foreach ($this->getHeaders() as $index => $header) {
            $col  = $columns[$index];
            $cell = $col . $headerRow;
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->applyFromArray($headerStyle);
            $sheet->getColumnDimension($col)->setWidth($this->columnWidths[$index]);
        }

        // Tinggi baris header
        $sheet->getRowDimension($headerRow)->setRowHeight(48);

        // =========================================================
        // BARIS DATA
        // =========================================================
        $dataStyle = [
            'font'      => ['size' => 9],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ];

        foreach ($registrations as $rowIndex => $registration) {
            $row     = $rowIndex + 2; // Row 2 dan seterusnya
            $aplData = $registration->aplData;
            $nik     = $aplData?->identity_number ?? '';

            // Ekstrak kode kota & provinsi dari NIK (standar BPS/BNSP)
            // KODE PROVINSI = 4 digit pertama NIK    → misal 3603
            // KODE KOTA     = digit ke-3 & ke-4 NIK → misal 03  (bagian kota dari kode wilayah)
            $kodeProvinsi = strlen($nik) >= 4 ? substr($nik, 0, 4) : '';
            $kodeKota     = strlen($nik) >= 4 ? substr($nik, 2, 2) : '';

            // Format tanggal lahir: dd/mm/yy
            $tglLahir = null;
            if ($aplData?->date_of_birth) {
                $tglLahir = \Carbon\Carbon::parse($aplData->date_of_birth)->format('d/m/Y');
            }

            // Format tanggal uji: dd/mm/yy
            $tglUji = null;
            if ($registration->assessed_at) {
                $tglUji = \Carbon\Carbon::parse($registration->assessed_at)->format('d/m/Y');
            }

            // Jenis kelamin
            $gender = null;
            if ($aplData?->gender) {
                $genderValue = $aplData->gender instanceof \App\Enums\Gender
                    ? $aplData->gender->value
                    : $aplData->gender;
                $gender = strtolower($genderValue) === 'male' ? 'L' : 'P';
            }

            // Kode pendidikan
            $kodePendidikan = $aplData?->education?->title ?? '';

            // Nomor registrasi asesor
            $nomorRegistrasiAsesor = $registration->assessor?->registration_number ?? '';

            // Kode pekerjaan
            $kodePekerjaan = $aplData?->job_code ?? $registration->candidate?->profile?->job_code ?? '';

            // Kode sumber anggaran
            $sumberAnggaran = '';
            $asesiType = $registration->asesi_type ?? $registration->candidate?->profile?->asesi_type;
            if ($asesiType === 'institusi') {
                $sumberAnggaran = 'Instansi';
            } elseif ($asesiType === 'pribadi') {
                $sumberAnggaran = 'Pribadi';
            }

            // Kode kementrian
            $kodeKementrian = $registration->candidate?->registration_number ?? '';

            // Row data
            $rowData = [
                $rowIndex + 1,                    // A: No
                $registration->candidate?->name ?? '', // B: Nama Asesi
                $nik,                             // C: NIK
                $aplData?->place_of_birth ?? '',  // D: Tempat Lahir
                $tglLahir,                        // E: Tanggal Lahir
                $gender,                          // F: Jenis Kelamin
                $aplData?->address ?? '',         // G: Tempat Tinggal
                $kodeKota,                        // H: Kode Kota
                $kodeProvinsi,                    // I: Kode Provinsi
                $aplData?->phone_number ?? '',    // J: Telp
                $registration->candidate?->email ?? '', // K: Email
                $kodePendidikan,                  // L: Kode Pendidikan
                $kodePekerjaan,                   // M: Kode Pekerjaan
                '',                               // N: Kode Jadwal (kosong)
                $tglUji,                          // O: Tanggal Uji
                $nomorRegistrasiAsesor,           // P: Nomor Registrasi Asesor
                $sumberAnggaran,                  // Q: Kode Sumber Anggaran
                $kodeKementrian,                  // R: Kode Kementrian
                '',                               // S: K/BK (kosong — diisi manual)
            ];

            foreach ($rowData as $colIndex => $value) {
                $col  = $columns[$colIndex];
                $cell = $col . $row;
                $sheet->setCellValue($cell, $value ?? '');
                $sheet->getStyle($cell)->applyFromArray($dataStyle);
            }

            // Center alignment untuk kolom tertentu
            $centerCols = ['A', 'E', 'F', 'H', 'I', 'L', 'M', 'N', 'O', 'Q', 'R', 'S'];
            foreach ($centerCols as $col) {
                $sheet->getStyle($col . $row)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Tinggi baris data
            $sheet->getRowDimension($row)->setRowHeight(30);
        }

        // =========================================================
        // FREEZE PANE & AUTOFILTER
        // =========================================================
        $sheet->freezePane('A2');
        $lastRow     = $registrations->count() + 1;
        $sheet->setAutoFilter('A1:S' . ($lastRow > 1 ? $lastRow : 2));

        // Page setup
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        return $spreadsheet;
    }
}
