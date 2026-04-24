<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div 
        x-data="{
            signaturePad: null,
            state: @entangle($getStatePath()),
            isDrawing: false,
            isLocked: false,
            isUpdatingState: false,

            init() {
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
                    if (this.isDrawing || this.isLocked || this.isUpdatingState) return;
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
                        if (this.state) {
                            this.signaturePad.fromDataURL(this.state);
                        }
                    }
                }, 30);

                this.signaturePad.addEventListener('beginStroke', () => {
                    this.isDrawing = true;
                    this.isLocked = true;
                });

                this.signaturePad.addEventListener('endStroke', () => {
                    this.isDrawing = false;
                    this.isUpdatingState = true;
                    const dataUrl = this.signaturePad.toDataURL('image/png');
                    this.state = dataUrl;
                    setTimeout(() => {
                        this.isLocked = false;
                        this.isUpdatingState = false;
                    }, 800);
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
                this.isUpdatingState = true;
                this.signaturePad.clear();
                this.state = null;
                setTimeout(() => {
                    this.resizeCanvas();
                    setTimeout(() => {
                        this.isLocked = false;
                        this.isUpdatingState = false;
                    }, 300);
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

        <div
            x-ref="container"
            wire:ignore
            class="border rounded shadow-inner"
            style="height: 300px; width: 100%; background-color: white;"
        >
            <canvas
                x-ref="canvas"
                style="touch-action: none; cursor: crosshair; display: block;"
            ></canvas>
        </div>

        <div class="mt-2 flex items-center space-x-3">
            <button
                type="button"
                @click="clear()"
                class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition"
            >
                CLEAR
            </button>
            <span class="text-xs text-gray-400 italic">Signature is saved as you draw.</span>
        </div>
    </div>
</x-dynamic-component>