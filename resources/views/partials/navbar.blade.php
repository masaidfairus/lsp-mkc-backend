@props([
    'name' => 'search',
    'type' => 'text',
    'value' => null
])

<nav class="bg-white overflow-hidden z-50">
    <div class="bg-[#E9E9E9] flex justify-between px-6 py-8 gap-2 ">
            <div class="flex gap-4">
               <form class="relative">
                <label for="search" class="cursor-text">
                    <i class="fa-solid fa-magnifying-glass 
                        absolute left-0 top-1/2 -translate-y-1/2 
                        text-black text-4xl px-8
                        transition-all duration-300">
                    </i>
                </label>

    <input 
        id="search"
        type="text"
        class="peer rounded-full h-24 w-24
            pl-20 focus:w-[38.2rem]
            bg-white transition-all duration-300 
            text-black text-4xl">
</form>


                <x-circle-button>
                <i class="fa-regular fa-envelope text-6xl"></i>
            </x-circle-button>

            <x-circle-button>
                <i class="fa-regular fa-bell text-6xl"></i>
            </x-circle-button>
        </div>

        <div class="flex gap-6">
            <div class="tooltip shrink-0">
                <div class="tooltip-content">
                    <div>Fadhel</div>
                    <div>fadhelsuradipraja@gmail.com</div>
                </div>
                <x-profile-picture />
             </div>

            <div class="drawer drawer-end flex justify-end items-center">
                <input id="my-drawer-5" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content">
                        <label for="my-drawer-5" class="drawer-button text-black bg-white text-6xl rounded-sm"><i class="fa-solid fa-bars"></i></label>
                </div>
                <div class="drawer-side">
                    <label for="my-drawer-5" aria-label="close sidebar" class="drawer-overlay"></label>
                    <div class="menu flex flex-col justify-between bg-[#E9E9E9] min-h-full w-[80vw] py-20 px-20 space-y-28">
                        <div class="flex flex-col justify-between h-full">
                            <div class="w-full flex justify-center items-center px-4 py-10 mb-40">
                                <img src="{{asset('images/logo_lsp.png')}}" alt="" class="w-100">
                            </div>

                            <div class="flex flex-col gap-12">
                                    <ul class="text-black">
                                    <div class="mb-6 text-4xl">
                                        <h1>Menu</h1>
                                    </div>
                                    <li class="text-5xl flex flex-row gap-4 mb-8">
                                    <i class="fa-solid fa-chart-line text-[#0C6105]"></i> <a href="">Dashboard</a>
                                    </li>
                                    <li class="text-5xl flex flex-row gap-4 text-[#888888] mb-8">
                                    <i class="fa-solid fa-calendar"></i> <a href="">Calendar</a>
                                    </li>
                                    <li class="text-5xl flex flex-row gap-4 text-[#888888] mb-8">
                                    <i class="fa-solid fa-inbox"></i> <a href="">Inbox</a>
                                    </li>
                                </ul>

                                <ul class="text-black">
                                    <div class="mb-6 text-4xl">
                                        <h1>General</h1>
                                    </div>
                                    <li class="text-5xl flex flex-row gap-4 text-[#888888] mb-8">
                                    <i class="fa-solid fa-gear"></i> <a href="">Settings</a>
                                    </li>
                                    <li class="text-5xl flex flex-row gap-4 text-[#888888] mb-8">
                                    <i class="fa-solid fa-circle-info"></i> <a href="">Help</a>
                                    </li>
                                    <li class="text-5xl flex flex-row gap-4 text-[#888888] mb-8">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> <a href="">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div 
                            class="relative w-full h-[25vh] bg-cover bg-center rounded-4xl"
                            style="background-image: url('{{ asset('images/calendar_bg.png') }}');">

                            <div class="absolute inset-0 bg-black/30 rounded-4xl"></div>

                            <div class="relative flex flex-col justify-between z-10 px-8 py-12 h-full text-white">
                                <div class="text-4xl">
                                    <h1><i class="fa-solid fa-calendar"></i> <a href="">Calendar</a></h1>
                                </div>

                                <div class="flex flex-col gap-4">
                                    <h1 class="text-6xl">Lorem Ipsum <br> Dolor Sit Amet</h1>
                                    <p class="text-2xl">Lorem Ipsum Dolor Sit Amet</p>
                                </div>
                                
                                <button class="bg-white p-6 text-4xl rounded-full text-black">
                                    <h1>Get Locking In</h1>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</nav>