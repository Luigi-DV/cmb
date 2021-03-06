<footer class="text-gray-600 dark:text-white dark:bg-black body-font pt-5">
    <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
        <a class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900 dark:text-orange-applus">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-orange-applus rounded-full" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <span class="ml-3 text-xl">{{ config('app.name', 'CMB') }}</span>
        </a>
        <p class="text-sm text-gray-500 dark:text-white sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">
            ©{{ date('Y') }} {{ config('app.company', 'Applus Laboratories') }}
        </p>
        <div class="grid grid-cols-2 gap-8 sm:ml-auto sm:mt-0 mt-4 place-items-end">
            <a href="{{route('redirection.link')}}?value={{config('app.project_tool.url')}}" class="flex items-center text-gray-500 dark:text-white hover:text-orange-applus">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="h-5 w-5" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M11.571 11.513H0a5.218 5.218 0 0 0 5.232 5.215h2.13v2.057A5.215 5.215 0 0 0 12.575 24V12.518a1.005 1.005 0 0 0-1.005-1.005zm5.723-5.756H5.736a5.215 5.215 0 0 0 5.215 5.214h2.129v2.058a5.218 5.218 0 0 0 5.215 5.214V6.758a1.001 1.001 0 0 0-1.001-1.001zM23.013 0H11.455a5.215 5.215 0 0 0 5.215 5.215h2.129v2.057A5.215 5.215 0 0 0 24 12.483V1.005A1.001 1.001 0 0 0 23.013 0Z"/></svg>
                <span class="ml-2">{{config('app.project_tool.provider')}}</span>
            </a>

            <a href="{{route('help.center')}}" class="flex items-center text-gray-500 dark:text-white hover:text-orange-applus">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="ml-2">Help Center</span>
            </a>
        </div>
    </div>
</footer>
