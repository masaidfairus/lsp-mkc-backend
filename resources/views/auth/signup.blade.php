<x-guest-layout class="relative h-[100vh] overflow-hidden font-['Jakarta']">

    {{-- Background (ONLY THIS PAGE) --}}
    <div class="absolute inset-0 z-0">
        <img
            src="{{ asset('images/auth_bg.png') }}"
            alt="Auth Background"
            class="w-full h-full object-cover"
        >
    </div>

    {{-- Content --}}
    <div class="relative z-10 flex flex-col lg:grid grid-cols-2 min-h-screen pt-96 lg:pt-0">

        {{-- Left empty / overlay space --}}
        <div class="gap-4 lg:gap-0 text-white hidden lg:flex flex-col justify-center items-center lg:items-start lg:justify-end p-14">
            <div class="italic font-bold">
                <h1 class="text-4xl lg:text-xl">
                    Your Career Starts Here.
                </h1>
            </div>

            <div class="text-xl text-center lg:text-start">
                <p>
                    Ayo daftarkan akunmu, dan tumbuh lebih baik bersama-sama, <br /> sebagaimana seharusnya
                </p>
            </div>
        </div>

        {{-- Right form --}}
        <div class="flex items-center justify-center p-12 lg:p-8">

            <x-input-container
                class="bg-[#4D9242] h-full w-full max-h-[calc(100vh-4rem)]
                       rounded-xl flex flex-col justify-center gap-2 p-12 lg:px-18"
                id="signupForm"
                action="/api/register"
            >

            <div class="absolute right-12 top-[20.5%] lg:right-8 lg:top-8 w-36">
                <img src="{{asset('images/element_login.png')}}" alt="">
            </div>

                {{-- Heading --}}
                <div class="text-white flex flex-col gap-2 mb-4">
                    <h1 class="text-5xl lg:text-[2.75rem] leading-tight">
                        Start Your Future Here
                    </h1>
                    <p class="text-xl lg:text-lg opacity-90">
                        Create your account to start your learning journey.
                    </p>
                </div>

                {{-- Form --}}
                <div class="flex flex-col gap-4 text-white">
                    <div class="gap-2 flex flex-col">
                        <label for="name" class="label text-white text-xl lg:text-sm">Nama Pengguna</label>
                        <x-input name="name" type="text" placeholder="Masukkan nama pengguna..." />
                    </div>

                    <div class="gap-2 flex flex-col">
                        <label for="identity_type" class="label text-white text-xl lg:text-sm">Tipe Identitas</label>
                        <select name="identity_type" class="select bg-[#4D9242] border border-[#ACCD3A] focus:ring-0 focus:outline-none w-full">
                            <option disabled selected>Pilih identitas yang ingin kamu daftarkan</option>
                            <option value="id">NIK / KTP</option>
                            <option value="passport">Passpor</option>
                        </select>
                    </div>

                    <div class="gap-2 flex flex-col">
                        <label for="identity_number" class="label text-white text-xl lg:text-sm">No Identitas</label>
                        <x-input name="identity_number" type="number" placeholder="Masukkan no identitas..." />
                    </div>

                    <div class="gap-2 flex flex-col">
                        <label for="email" class="label text-white text-xl lg:text-sm">Email</label>
                        <x-input name="email" type="email" placeholder="Masukkan email..." />
                    </div>

                    <div class="gap-2 flex flex-col">
                        <label for="password" class="label text-white text-xl lg:text-sm">Kata Sandi</label>
                        <x-password name="password" placeholder="Masukkan sandi..." />
                    </div>

                    <div class="gap-2 flex flex-col">
                        <label for="password_confirmation" class="label text-white text-xl lg:text-sm">Konfirmasi Kata Sandi</label>
                        <x-password name="password_confirmation" placeholder="Konfirmasi sandi..." :showShow="true" />
                    </div>
                </div>

                <x-button 
                class="mt-10"
                type="submit"
                >
                    Daftar
                </x-button>

                  <div class="divider text-xl lg:text-sm text-white">Atau Daftar Dengan</div>

                  <button class="btn text-xl lg:text-sm btn-outline bg-transparent text-white border-[#CDF447] p-8 lg:p-4">
                    <svg aria-label="Google logo" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g><path d="m0 0H512V512H0" fill="#fff"></path><path fill="#34a853" d="M153 292c30 82 118 95 171 60h62v48A192 192 0 0190 341"></path><path fill="#4285f4" d="m386 400a140 175 0 0053-179H260v74h102q-7 37-38 57"></path><path fill="#fbbc02" d="m90 341a208 200 0 010-171l63 49q-12 37 0 73"></path><path fill="#ea4335" d="m153 219c22-69 116-109 179-50l55-54c-78-75-230-72-297 55"></path></g></svg>
                   Google
                    </button>

                     {{-- Footer --}}
                <div class="flex justify-center items-center text-xl text-white lg:text-sm mt-4">
                    <p>
                        Sudah memiliki akun?
                    </p>
                    <x-hyperlink
                        route="login"
                        class="link link-hover ml-2 text-white"
                    >
                        Masuk Disini
                    </x-hyperlink>
                </div>

            </x-input-container>
        </div>
        
        <div class="gap-4 lg:gap-0 text-white lg:hidden flex flex-col justify-center items-center lg:items-start lg:justify-end p-14">
            <div class="italic font-bold">
                <h1 class="text-4xl lg:text-xl">
                    Your Career Starts Here.
                </h1>
            </div>

            <div class="text-xl text-center lg:text-start">
                <p>
                    Ayo daftarkan akunmu, dan tumbuh lebih baik bersama-sama, <br /> sebagaimana seharusnya
                </p>
            </div>
        </div>

    </div>

</x-guest-layout>
