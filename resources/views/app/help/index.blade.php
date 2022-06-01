<x-app-layout>


    <div class="h-screen font-nunito antialiased bg-gray-100 text-gray-900 my-16 flex items-center justify-center">

        <div class="container mx-auto px-4 sm:px-8 max-w-3xl">

            <div class="main-title my-8">
                <h1 class="font-bold text-2xl text-center">How can we help you?</h1>
            </div>


            <div class="main-question mb-8 flex flex-col divide-y text-gray-800 text-base">
                <div class="item px-6 py-6" x-data="{isOpen : false}">
                    <a href="#" class="flex items-center justify-between" @click.prevent="isOpen = true">
                        <h4 :class="{'text-orange-applus font-bold' : isOpen == true}">{{__('Departments')}}</h4>
                        <svg
                            class="w-5 h-5 text-gray-500"
                            fill="none" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div x-show="isOpen" @click.away="isOpen = false" class="mt-3" :class="{'text-gray-600' : isOpen == true}" x-transition.duration.150ms>
                        {{__('Each Department have their own configuration and database, each is independent of the other. ')}}
                    </div>
                </div>

                <div class="item px-6 py-6" x-data="{isOpen : false}">
                    <a href="#" class="flex items-center justify-between capitalize" @click.prevent="isOpen = true">
                        <h4 :class="{'text-orange-applus font-bold' : isOpen == true}">{{__('Fix problems & request updates')}}</h4>
                        <svg
                            class="w-5 h-5 text-gray-500"
                            fill="none" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div x-show="isOpen" @click.away="isOpen = false" class="mt-3" :class="{'text-gray-600' : isOpen == true}" x-transition.duration.150ms>
                        {{__('To fix problems please fill a')}} <span class="font-bold text-orange-applus">Support Request</span> {{__('with the problem data and information.')}}
                        <div class="py-5">
                            {{__('By contrast if you want to request any update please')}}
                            <span class="font-bold text-orange-applus">{{__('Write a Feedback')}}</span>
                            {{__('to request')}} <span class="font-semibold italic">{{('minor changes')}}</span> {{__('or fill a')}} <span class="font-bold text-orange-applus">Support Request</span> <span class="font-semibold italic">{{__('with any major updates requests.')}}</span>
                        </div>
                    </div>
                </div>

                <div class="item px-6 py-6" x-data="{isOpen : false}">
                    <a href="#" class="flex items-center justify-between" @click.prevent="isOpen = true">
                        <h4 :class="{'text-orange-applus font-bold' : isOpen == true}">{{__('Statistics')}}</h4>
                        <svg
                            class="w-5 h-5 text-gray-500"
                            fill="none" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div x-show="isOpen" @click.away="isOpen = false" class="mt-3" :class="{'text-gray-600' : isOpen == true}" x-transition.duration.150ms>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Placeat, ex. Expedita sunt enim, vel amet cumque nulla illum harum. Similique!
                    </div>
                </div>

                <div class="item px-6 py-6" x-data="{isOpen : false}">
                    <a href="#" class="flex items-center justify-between" @click.prevent="isOpen = true">
                        <h4 :class="{'text-orange-applus font-bold' : isOpen == true}">Search on your phone or tablet</h4>
                        <svg
                            class="w-5 h-5 text-gray-500"
                            fill="none" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div x-show="isOpen" @click.away="isOpen = false" class="mt-3" :class="{'text-gray-600' : isOpen == true}" x-transition.duration.150ms>
                        {{__('With responsive design, users will access the same basic file through their browser, regardless of device')}}.
                    </div>
                </div>
            </div>

            <div class="main-images mb-8 ">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="group bg-white rounded-lg shadow-lg overflow-hidden text-center hover:bg-orange-applus">
                        <a href="#" class="text-gray-300 group-hover:text-white">
                            <div class="flex w-full py-2 justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            <span class="text-center p-2 text-gray-700 group-hover:text-white text-sm inline-block w-full">{{__('Support Request')}}</span>
                        </a>
                    </div>

                    <div class="group bg-white rounded-lg shadow-lg overflow-hidden hover:bg-orange-applus">
                        <a href="#" class="text-gray-300 group-hover:text-white">
                            <div class="flex w-full py-2 justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <span class="text-center p-2 text-gray-700 group-hover:text-white text-sm inline-block w-full">{{__('Write feedback')}}</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
