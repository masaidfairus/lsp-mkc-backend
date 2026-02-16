@props([
    'name' => 'search',
    'type' => 'text',
    'value' => null
])

<nav class="bg-white overflow-hidden z-50 lg:p-0 lg:py-4">
    <div class="bg-[#E9E9E9] flex justify-between px-6 py-8 gap-2 lg:rounded-xl">
            <div class="flex gap-4">
               <form class="relative">
                <label for="search" class="cursor-text">
                    <i class="fa-solid fa-magnifying-glass 
                        absolute left-0 top-1/2 -translate-y-1/2 lg:-translate-y-2.5 lg:-left-4.5
                        text-black text-4xl lg:text-xl px-8
                        transition-all duration-300">
                    </i>
                </label>

                    <input 
                        id="search"
                        type="text"
                        placeholder="Search for something..."
                        class="peer rounded-full h-24 w-24
                        pl-24 focus:w-[38.2rem] lg:w-[30rem] lg:h-12 lg:pl-12 lg:focus:w-[30rem] lg:focus:ring-0 lg:focus:outline-0
                        bg-white transition-all duration-300 
                            text-black text-4xl lg:text-base">
                </form>


                <div class="flex gap-4 lg:hidden">
                    <x-circle-button>
                        <i class="fa-regular fa-envelope text-6xl lg:text-xl"></i>
                    </x-circle-button>

                    <x-circle-button>
                        <i class="fa-regular fa-bell text-6xl lg:text-xl"></i>
                    </x-circle-button>
                </div>
        </div>

        {{-- Tooltip Android --}}
        <div class="flex gap-6">
            <div class="tooltip shrink-0">
                <div class="tooltip-content block lg:hidden">
                    <div>Fadhel</div>
                    <div>fadhelsuradipraja@gmail.com</div>
                </div>
                <div class="flex gap-4">
                    <div class="lg:flex gap-2 hidden">
                        <x-circle-button>
                            <i class="fa-regular fa-envelope text-6xl lg:text-xl"></i>
                        </x-circle-button>

                        <x-circle-button>
                            <i class="fa-regular fa-bell text-6xl lg:text-xl"></i>
                        </x-circle-button>
                    </div>
                    <x-profile-picture />
                    <div class="hidden lg:block">
                        <h1 class="text-black">Mohammad fadhel</h1>
                        <p class="text-[#6D6D6D]">mohammadfadhel@gmail.com</p>
                    </div>
                </div>
             </div>
        {{-- Tooltip Android End --}}

        {{-- Drawer --}}
            <div class="drawer drawer-end flex lg:hidden justify-end items-center">
                <input id="my-drawer-5" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content">
                        <label for="my-drawer-5" class="drawer-button text-black bg-white text-6xl rounded-sm"><i class="fa-solid fa-bars"></i></label>
                </div>
                <div class="drawer-side">
                    <label for="my-drawer-5" aria-label="close sidebar" class="drawer-overlay"></label>
                    @include('partials.sidebar')
                </div>
            </div>
        {{-- Drawer End --}}
        </div>
        
    </div>
</nav>