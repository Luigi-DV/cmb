<div x-cloak class="h-100" x-data="{show: false }" @mouseover.away = "show = false">
    <div class="relative">
        <!-- Item active: "text-gray-900 dark:text-gray-200", Item inactive: "text-gray-500 dark:text-white" -->
        <button type="button" @mouseover="show = true" class="text-gray-500 dark:text-white dark:text-white group bg-transparent rounded-md inline-flex items-center text-base font-medium hover:text-orange-applus focus:outline-none" aria-expanded="false">
            <x-navigation.anonymous.dynamic-link :active="request()->routeIs('statistics.*')">
                <span>{{__('Statistics')}}</span>
            </x-navigation.anonymous.dynamic-link>
        </button>
        <div x-show="show" @click.away="show = false" class="absolute z-10 -ml-4 transform px-2 w-screen max-w-sm sm:px-0 lg:ml-0 lg:left-1/2 lg:-translate-x-1/2">
            <x-navigation.options.statistics.links.statistics-links/>
        </div>
    </div>
</div>
