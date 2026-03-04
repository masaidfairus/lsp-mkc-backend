<x-guest-layout>
    <!-- Bg Img -->
      <div class="absolute inset-0 z-0">
        <img
            src="{{ asset('images/verif_bg.png') }}"
            alt="Verification Background"
            class="w-full h-full object-cover"
        >
    </div>
    {{-- Card Land --}}
    <div class="h-[100vh] flex justify-center items-center ">
        {{-- Card Wrap --}}
            <div class="w-[80vw] h-[40vh] lg:h-[] lg:w-[25vw]">
            <!-- Card -->
                <div class="relative z-10 bg-black shadow-2xl lg:shadow-sm shadow-white w-full h-full lg:h-fit flex flex-col justify-center items-center rounded-lg py-12 lg:py-20 px-16 lg:px-6">
                    {{-- Layout Wrap --}}
                    <div class="flex flex-col justify-center items-center space-y-8 lg:space-y-2 w-full">
                        {{-- Icon --}}
                        <div class="mb-20 lg:mb-4 text-white text-8xl lg:text-4xl">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        {{-- Title --}}
                        <div class="text-center text-6xl lg:text-2xl text-white">
                            <h1>Email Successfully <br> Verified</h1>
                        </div>
                        {{-- Desc --}}
                        <div class="text-[1.8rem] lg:text-sm text-center font-light text-white">
                            <p>
                                Mari bersiap untuk masuk, mulailah dengan <br> menyiapkan lingkungan kerja anda!🎊📝
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</x-guest-layout>