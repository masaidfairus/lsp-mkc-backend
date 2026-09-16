@php
    $statePath = $getStatePath();
    $shapePath = str_contains($statePath, '.') 
        ? (string) Illuminate\Support\Str::of($statePath)->beforeLast('.')->append('.signature_shape')
        : 'signature_shape';
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div 
        x-data="{
            signaturePad: null,
            state: @entangle($getStatePath()),
            shapePath: @js($shapePath),
            isDrawing: false,
            isLocked: false,
            charCount: 0,
            lastSaved: null,

            init() {
                console.log('Signature Pad Init. Paths:', { state: @js($statePath), shape: this.shapePath });
                
                // Initial char count if data exists
                const initialShape = this.$wire.get(this.shapePath);
                if (initialShape) this.charCount = initialShape.length;

                if (typeof SignaturePad === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js';
                    script.onload = () => this.$nextTick(() => this.setup());
                    document.head.appendChild(script);
                } else {
                    this.$nextTick(() => this.setup());
                }

                document.addEventListener('filament-modal-opened', () => {
                    setTimeout(() => this.resizeCanvas(), 300);
                });

                this.$watch('state', (newVal, oldVal) => {
                    if (this.isDrawing || this.isLocked) return;
                    if (newVal === null && oldVal && typeof oldVal === 'string' && oldVal.startsWith('data:') && this.signaturePad) {
                        this.signaturePad.clear();
                        setTimeout(() => this.resizeCanvas(), 100);
                    }
                });
            },

            setup() {
                const canvas = this.$refs.canvas;
                this.signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)',
                    minWidth: 0.5,
                    maxWidth: 2.5,
                });

                const waitForSize = setInterval(() => {
                    const container = this.$refs.container;
                    const rect = container.getBoundingClientRect();
                    if (rect.width > 0 && rect.height > 0) {
                        clearInterval(waitForSize);
                        this.resizeCanvas();
                        
                        // Try to load existing data
                        if (this.state) {
                            this.signaturePad.fromDataURL(this.state);
                        } else {
                            const currentShape = this.$wire.get(this.shapePath);
                            if (currentShape) {
                                try {
                                    const strokes = JSON.parse(currentShape);
                                    if (Array.isArray(strokes)) this.signaturePad.fromData(strokes);
                                } catch(e) {}
                            }
                        }
                    }
                }, 30);

                this.signaturePad.addEventListener('beginStroke', () => {
                    this.isDrawing = true;
                    this.isLocked = true;
                });

                this.signaturePad.addEventListener('endStroke', () => {
                    this.isDrawing = false;
                    const dataUrl = this.signaturePad.toDataURL('image/png');
                    this.state = dataUrl;
                    
                    // Save shape data (strokes) via direct $wire call
                    const strokes = this.signaturePad.toData();
                    const shapeJson = JSON.stringify(strokes);
                    this.charCount = shapeJson.length;
                    
                    this.$wire.set(this.shapePath, shapeJson);
                    this.lastSaved = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

                    setTimeout(() => {
                        this.isLocked = false;
                    }, 150);
                });

                let lastW = 0, lastH = 0;
                const ro = new ResizeObserver(() => {
                    if (this.isDrawing || this.isLocked) return;
                    const container = this.$refs.container;
                    const rect = container.getBoundingClientRect();
                    if (Math.abs(rect.width - lastW) < 10 && Math.abs(rect.height - lastH) < 10) return;
                    lastW = rect.width;
                    lastH = rect.height;
                    setTimeout(() => {
                        if (!this.isDrawing && !this.isLocked) this.resizeCanvas();
                    }, 200);
                });
                ro.observe(this.$refs.container);
            },

            resizeCanvas() {
                const canvas = this.$refs.canvas;
                const container = this.$refs.container;
                if (!canvas || !container) return;
                if (this.isDrawing) return;

                const rect = container.getBoundingClientRect();
                if (rect.width === 0 || rect.height === 0) return;

                const ratio = window.devicePixelRatio || 1;
                const displayWidth = rect.width;
                const displayHeight = rect.height;

                canvas.style.width = displayWidth + 'px';
                canvas.style.height = displayHeight + 'px';

                const data = this.signaturePad ? this.signaturePad.toData() : [];

                canvas.width = Math.round(displayWidth * ratio);
                canvas.height = Math.round(displayHeight * ratio);
                canvas.getContext('2d').scale(ratio, ratio);

                this.signaturePad.clear();

                if (data && data.length > 0) {
                    this.signaturePad.fromData(data);
                } else if (this.state && typeof this.state === 'string' && this.state.startsWith('data:')) {
                    this.signaturePad.fromDataURL(this.state);
                }
            },

            clear() {
                if (this.isDrawing) return;
                this.isLocked = true;
                this.signaturePad.clear();
                this.state = null;
                this.charCount = 0;
                this.lastSaved = null;
                this.$wire.set(this.shapePath, null);
                setTimeout(() => {
                    this.resizeCanvas();
                    setTimeout(() => {
                        this.isLocked = false;
                    }, 100);
                }, 50);
            }
        }"
        class="space-y-2 border-2 border-gray-300 dark:border-gray-700 rounded-lg p-2"
    >
        <div class="flex items-start gap-2 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 px-3 py-2">
            <svg class="w-4 h-4 mt-0.5 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-yellow-700 dark:text-yellow-300">
                <strong>Tip:</strong> Isi <strong>Name/Identifier</strong> terlebih dahulu sebelum membuat tanda tangan.
            </p>
        </div>

        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <template x-if="isDrawing">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800 animate-pulse">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                        MEREKAM...
                    </span>
                </template>
                <template x-if="!isDrawing && charCount > 10">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        VEKTOR AKTIF: <span x-text="charCount"></span> CHARS
                    </span>
                </template>
                <template x-if="!isDrawing && charCount <= 10">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        VEKTOR KOSONG
                    </span>
                </template>
            </div>
            <div class="text-[10px] text-gray-400 font-medium" x-show="lastSaved">
                Tersimpan otomatis: <span x-text="lastSaved"></span>
            </div>
        </div>

        <div
            x-ref="container"
            wire:ignore
            class="border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden transition-colors hover:border-gray-300 dark:hover:border-gray-700"
            style="height: 300px; width: 100%; background-color: white;"
        >
            <canvas
                x-ref="canvas"
                style="touch-action: none; cursor: crosshair; display: block;"
            ></canvas>
        </div>

        <div class="mt-2 flex items-center justify-between">
            <button
                type="button"
                @click="clear()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 text-red-600 border border-red-200 dark:border-red-900/50 rounded-md text-xs font-bold hover:bg-red-50 dark:hover:bg-red-900/10 transition shadow-sm"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                BERSIHKAN
            </button>
            <span class="text-[10px] text-gray-400 font-medium italic">Tanda tangan tersimpan otomatis secara real-time.</span>
        </div>
    </div>
</x-dynamic-component>