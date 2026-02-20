<div class="menu flex flex-col justify-between bg-[#E9E9E9] min-h-full w-[80vw] px-4 py-6 space-y-28 lg:space-y-14 lg:w-fit lg:rounded-xl">
                        <div class="pr-20 pb-20 pl-20 lg:pr-10 lg:pb-10 lg:pl-10 flex flex-col justify-between lg:justify-start h-full lg:h-fit">
                            <div class="w-full flex justify-center lg:justify-start items-center lg:items-start px-4 pt-4 mb-40 lg:mb-20">
                                <img src="{{asset('images/logo_lsp.png')}}" alt="" class="w-100 lg:w-40">
                            </div>

                            <div class="flex flex-col gap-12">
                                    <ul class="text-black">
                                    <div class="mb-6 text-4xl lg:text-lg">
                                        <h1>Menu</h1>
                                    </div>
                                    <li class="text-5xl lg:text-xl flex items-center flex-row gap-4 mb-8 lg:mb-2">
                                    <i class="fa-solid fa-chart-line text-[#0C6105]"></i> <a href="">Dashboard</a>
                                    </li>
                                    <li class="text-5xl lg:text-xl flex items-center flex-row gap-4 text-[#888888] mb-8 lg:mb-2">
                                    <i class="fa-solid fa-calendar"></i> <a href="">Calendar</a>
                                    </li>
                                    <li class="text-5xl lg:text-xl flex items-center flex-row gap-4 text-[#888888] mb-8 lg:mb-2">
                                    <i class="fa-solid fa-inbox"></i> <a href="">Inbox</a>
                                    </li>
                                </ul>

                                <ul class="text-black">
                                    <div class="mb-6 text-4xl lg:text-lg">
                                        <h1>General</h1>
                                    </div>
                                    <li class="text-5xl lg:text-xl flex items-center flex-row gap-4 text-[#888888] mb-8 lg:mb-2">
                                    <i class="fa-solid fa-gear"></i> <a href="">Settings</a>
                                    </li>
                                    <li class="text-5xl lg:text-xl flex items-center flex-row gap-4 text-[#888888] mb-8 lg:mb-2">
                                    <i class="fa-solid fa-circle-info"></i> <a href="">Help</a>
                                    </li>
                                    <li class="text-5xl lg:text-xl flex items-center flex-row gap-4 text-[#888888] mb-8 lg:mb-2">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> <a href="">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div 
                            class="relative w-full h-[25vh] lg:w-[16vw] lg:h-fit bg-cover bg-center rounded-4xl lg:rounded-xl"
                            style="background-image: url('{{ asset('images/calendar_bg.png') }}');">

                            <div class="absolute inset-0 bg-black/30 rounded-4xl"></div>

                            <div class="relative flex flex-col justify-between z-10 px-8 py-12 lg:px-4 lg:py-6 h-full lg:space-y-6 text-white">
                                <div class="text-4xl lg:text-lg">
                                    <h1><i class="fa-solid fa-calendar"></i> <a href="">Calendar</a></h1>
                                </div>

                                <div class="flex flex-col gap-4 lg:gap-1">
                                    <h1 class="text-6xl lg:text-xl">Lorem Ipsum <br> Dolor Sit Amet</h1>
                                    <p class="text-2xl lg:text-sm">Lorem Ipsum Dolor Sit Amet</p>
                                </div>
                                
                                <button class="bg-white p-6 lg:px-2 lg:py-3 text-4xl lg:text-base rounded-full lg:w-full text-black">
                                    <h1>Get Locking In</h1>
                                </button>
                            </div>
                        </div>

                    </div>