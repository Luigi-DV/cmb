<div x-data="{show: false }" class="relative z-50 top-0 inset-x-0 p-2 transform origin-top-right lg:hidden" @click.outside="show = false">
    <div class="transition ease-in-out delay-150 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white dark:bg-gray-800 divide-y-2 divide-gray-50">
        <div class="pt-5 pb-6 px-5">
            <div class="flex items-center justify-between">
                <div>
                    <x-ui.application-logo />
                </div>
                <div class="-mr-2">
                    <button type="button" @click="show = !show" class="bg-transparent rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-orange-applus focus:bg-transparent focus:outline-none focus:ring-2 focus:ring-inset focus:ring-yellow-500">
                        <span x-show="!show" class="sr-only">Open menu</span>
                        <span x-show="show" class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': show, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" ></path>
                            <path :class="{'hidden': ! show, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div x-cloak x-show="show" class="bg-white relative z-50 transition ease-in-out delay-150">
                <div class="relative z-50 py-12 px-5 space-y-6">
                    <div class="grid grid-cols-1 gap-y-4 gap-x-10">
                        <!-- Department Navigation
                            foreach(config('app.departments') as $department)
                                <a href="{ route('department.statistics.index', $department['id']) }}" class="group flex items-center text-right text-base font-medium text-gray-900 dark:text-gray-200 hover:text-orange-applus capitalize">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-orange-applus text-light h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                    </svg>
                                    <span class="mr-2"><span class="uppercase">{$department['id']}}</span> {__('Statistics')}}</span>
                                </a>                        endforeach-->



                        <a href="{{ route('docs') }}" class="group flex items-center text-right text-base font-medium text-gray-900 dark:text-gray-200 hover:text-orange-applus capitalize">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-orange-applus text-light h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="mr-2">{{__('Docs')}}</span>
                        </a>

                        <a href="#" class="group flex items-center text-right text-base font-medium text-gray-900 dark:text-gray-200 hover:text-orange-applus capitalize">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-orange-applus text-light h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="mr-2">{{__('Help center')}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
