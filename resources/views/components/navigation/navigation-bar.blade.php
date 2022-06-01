<div class="relative bg-white dark:bg-black">
    <div class="hidden lg:block mx-auto px-4 sm:px-6">
        <div class="flex z-50 justify-between items-center border-b-2 border-gray-100 dark:border-gray-900 py-6 md:justify-start md:space-x-10">
            <div class="flex justify-start lg:w-0 lg:flex-1">
                <x-ui.application-logo />
            </div>
            <div class="-mr-2 -my-2 md:hidden">
                <button type="button" class="bg-transparent rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-orange-applus hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                    <span class="sr-only">Open menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <!--Navigation CMB -->
            <nav class="hidden md:flex space-x-14">

                @if($department && $getUserByID($userID))
                    <div x-data="{show: false}" @click.outside="show = false" class="relative inline-block text-left">
                        <div>
                            <button @click="show = !show" type="button" class="capitalize inline-flex items-center justify-center w-full px-4 py-2 bg-white text-sm font-medium text-gray-700 " id="menu-button" aria-expanded="true" aria-haspopup="true">
                                {{$getUserByID($userID)->nom}}
                                <!-- Heroicon name: solid/chevron-down -->
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div x-cloak x-show="show"
                             x-transition:enter.duration.100ms
                             x-transition:leave.duration.75ms
                             x-transition.opacit
                             class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hover:bg-gray-100" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <form method="GET" action="{{url('/departments/'.$department.'/www/process/login.php')}}" role="none">
                                    <input type="hidden" name="action" value="logout"/>
                                    <button type="submit" class="text-gray-700 block w-full text-left px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-3">Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </nav>
        </div>
    </div>
    <x-navigation.mobile.navigation-bar/>
</div>
