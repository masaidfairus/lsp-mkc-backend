<div class="space-y-6">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $signatureRequest->document_label }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Peran Anda: <strong>{{ $signatureRequest->role }}</strong></p>
            <div class="mt-2 flex items-center gap-2">
                @if($signatureRequest->status === 'signed')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Telah Ditandatangani
                    </span>
                    <span class="text-xs text-gray-500">{{ $signatureRequest->signed_at->format('d M Y, H:i') }}</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Menunggu Tanda Tangan
                    </span>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ $pdfUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-800 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/40 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Lihat Dokumen PDF
            </a>
        </div>
    </div>

    @if($signatureRequest->status === 'pending')
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 uppercase tracking-wide">Area Tanda Tangan</h4>

            <div x-data="{
                signaturePad: null,
                isDrawing: false,
                charCount: 0,

                init() {
                    if (typeof SignaturePad === 'undefined') {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js';
                        script.onload = () => this.$nextTick(() => this.setup());
                        document.head.appendChild(script);
                    } else {
                        this.$nextTick(() => this.setup());
                    }
                },

                setup() {
                    const canvas = this.$refs.canvas;
                    this.signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)',
                        penColor: 'rgb(0, 0, 0)',
                        minWidth: 0.5,
                        maxWidth: 2.5,
                    });

                    this.resizeCanvas();
                    window.addEventListener('resize', () => this.resizeCanvas());

                    this.signaturePad.addEventListener('beginStroke', () => { this.isDrawing = true; });
                    this.signaturePad.addEventListener('endStroke', () => { 
                        this.isDrawing = false; 
                        this.charCount = JSON.stringify(this.signaturePad.toData()).length;
                    });
                },

                resizeCanvas() {
                    const canvas = this.$refs.canvas;
                    const container = this.$refs.container;
                    if (!canvas || !container) return;

                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = container.offsetWidth * ratio;
                    canvas.height = container.offsetHeight * ratio;
                    canvas.getContext('2d').scale(ratio, ratio);
                    this.signaturePad.clear();
                },

                clear() {
                    this.signaturePad.clear();
                    this.charCount = 0;
                },

                submit() {
                    if (this.signaturePad.isEmpty()) {
                        alert('Tanda tangan tidak boleh kosong!');
                        return;
                    }
                    const dataUrl = this.signaturePad.toDataURL('image/png');
                    $wire.submitSignature({{ $signatureRequest->id }}, dataUrl);
                }
            }">
                <div class="flex items-center justify-between mb-2 px-1">
                    <div class="text-[10px] font-bold text-gray-500">
                        <span x-show="isDrawing" class="text-blue-500 animate-pulse">MEREKAM...</span>
                        <span x-show="!isDrawing && charCount > 10" class="text-green-500">VEKTOR AKTIF (<span x-text="charCount"></span> CHARS)</span>
                    </div>
                    <button type="button" @click="clear()" class="text-xs text-red-600 font-semibold hover:underline">
                        Bersihkan Canvas
                    </button>
                </div>

                <div x-ref="container" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl overflow-hidden bg-white" style="height: 300px; width: 100%;">
                    <canvas x-ref="canvas" style="touch-action: none; cursor: crosshair; display: block; width: 100%; height: 100%;"></canvas>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" 
                        @click="submit()" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-sm">

                        <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                        <svg wire:loading class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span wire:loading.remove>Kirim TTD</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-6 uppercase tracking-wide">Data Tanda Tangan Tersimpan</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide font-medium">Tanda Tangan</p>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 flex items-center justify-center min-h-[160px]">
                        <img src="{{ $signatureRequest->raw_signature }}" class="max-h-[120px] object-contain" alt="Signature" />
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide font-medium">QR Code Validasi</p>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 flex items-center justify-center min-h-[160px]">
                        @if($signatureRequest->qr_code)
                            <img src="{{ $signatureRequest->qr_code }}" class="max-h-[120px] object-contain" alt="QR Code" />
                        @else
                            <p class="text-sm text-gray-400 italic">QR Code tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                <button type="button" 
                    wire:click="revertSignature({{ $signatureRequest->id }})"
                    wire:loading.attr="disabled"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus tanda tangan ini dan mengulanginya?')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus & Tanda Tangani Ulang
                </button>
            </div>
        </div>
    @endif
</div>
