<?php

namespace App\Filament\Resources\SignatureInboxResource\Pages;

use App\Filament\Resources\SignatureInboxResource;
use App\Models\SignatureRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class ViewSignatureInbox extends EditRecord
{
    protected static string $resource = SignatureInboxResource::class;

    protected ?string $heading = 'Tinjau & Tanda Tangani Dokumen';

    /**
     * Form.
     *
     * @param  Form  $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema(function ($record) {
                        if (!$record)
                            return [];

                        $requests = $record->signatureRequests()
                            ->where('user_id', auth()->id())
                            ->get();

                        if ($requests->isEmpty()) {
                            return [
                                Forms\Components\Placeholder::make('no_req')
                                    ->label('')
                                    ->content('Tidak ada dokumen yang perlu ditandatangani untuk pendaftaran ini.')
                            ];
                        }

                        $tabs = [];
                        foreach ($requests as $req) {
                            $isPending = $req->status === 'pending';

                            $pdfUrl = '#';
                            if ($req->document_label === 'FR.VA') {
                                $pdfUrl = \Illuminate\Support\Facades\URL::signedRoute('frva.print', ['registration' => $record->id]);
                            } else {
                                $routeName = strtolower(str_replace('.', '', $req->document_label)) . '.form';
                                try {
                                    $pdfUrl = \Illuminate\Support\Facades\URL::signedRoute($routeName, ['registration' => $record->id]);
                                } catch (\Exception $e) {
                                    // Fallback
                                }
                            }

                            $tabs[] = Forms\Components\Tabs\Tab::make($req->document_label . ($isPending ? ' ⏳' : ' ✅'))
                                ->schema([
                                    Forms\Components\ViewField::make("req_{$req->id}")
                                        ->label('')
                                        ->view('filament.forms.components.signature-inbox-detail')
                                        ->viewData([
                                            'signatureRequest' => $req,
                                            'pdfUrl' => $pdfUrl,
                                        ])
                                ]);
                        }

                        return [
                            Forms\Components\Tabs::make('Dokumen')
                                ->tabs($tabs)
                                ->columnSpanFull()
                        ];
                    })
                    ->columnSpanFull()
            ]);
    }

    // Handle the custom submission from Blade view
    public function submitSignature($requestId, $base64Data)
    {
        $req = SignatureRequest::where('id', $requestId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$req) {
            Notification::make()->title('Data tidak ditemukan atau tidak berhak')->danger()->send();
            return;
        }

        if (!$base64Data) {
            Notification::make()->title('Tanda tangan masih kosong')->warning()->send();
            return;
        }

        // Generate QR code using the same logic (simple-qrcode)
        // Format: Penanda Tangan: {name}\nPeran: {role}\nDokumen: {doc}\nWaktu: {time}
        $time = now()->format('Y-m-d H:i:s');
        $name = auth()->user()->name;
        $role = $req->role;
        $doc = $req->document_label;

        // Generate Verification URL for QR Code
        $qrText = route('ttd.cek', ['id' => $req->id]);

        // Build base64 QR (Using SVG format to avoid Imagick dependency)
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($qrText));

        $req->update([
            'status' => 'signed',
            'raw_signature' => $base64Data,
            'qr_code' => $qrBase64,
            'signed_at' => now(),
        ]);

        // AUTOMATICALLY CREATE ASESI SIGNATURE REQUEST
        \Log::info("Signature signed by assessor: label={$req->document_label}, reg_id={$req->registration_id}");
        
        // Forms that need Asesi signature after Assessor signs
        $needsAsesiSignature = ['AK.01', 'AK.02', 'AK.05', 'AK.06', 'IA.01', 'IA.02', 'IA.03', 'IA.05', 'IA.06', 'IA.07', 'IA.11', 'MAPA.01'];
        if (in_array($req->document_label, $needsAsesiSignature)) {
            $asesiId = $req->registration->user_id;
            
            $existingAsesiReq = \App\Models\SignatureRequest::where([
                'registration_id' => $req->registration_id,
                'user_id'         => $asesiId,
                'document_label'  => $req->document_label,
                'role'            => 'asesi',
            ])->first();

            if (!$existingAsesiReq) {
                \App\Models\SignatureRequest::create([
                    'registration_id' => $req->registration_id,
                    'user_id'         => $asesiId,
                    'document_label'  => $req->document_label,
                    'status'          => 'pending',
                    'signable_type'   => $req->signable_type,
                    'signable_id'     => $req->signable_id,
                    'role'            => 'asesi',
                ]);
            }
        }

        Notification::make()->title("Tanda tangan untuk $doc berhasil disimpan")->success()->send();
        
        // Check if there are any other pending requests for this record
        $hasPending = $req->registration->signatureRequests()
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if (!$hasPending) {
            return redirect(static::getResource()::getUrl('index'));
        }

        // Refresh page if there are still pending items
        return redirect(request()->header('Referer'));
    }

    /**
     * Revert signature.
     *
     * @param  mixed  $requestId
     * @return mixed
     */
    public function revertSignature($requestId)
    {
        $req = SignatureRequest::where('id', $requestId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$req)
            return;

        $req->update([
            'status' => 'pending',
            'raw_signature' => null,
            'qr_code' => null,
            'signed_at' => null,
        ]);

        Notification::make()->title("Tanda tangan untuk {$req->document_label} telah dihapus")->warning()->send();

        return redirect(request()->header('Referer'));
    }

    /**
     * Mengambil form actions.
     *
     * @return array
     */
    protected function getFormActions(): array
    {
        return []; // Sembunyikan tombol save default
    }
}
