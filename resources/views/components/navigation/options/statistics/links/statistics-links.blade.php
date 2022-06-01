<div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
    <div class="relative grid gap-6 bg-white dark:bg-gray-900 px-5 py-6 sm:gap-8 sm:p-8">
        @foreach(config('app.departments') as $department)
            <a href="{{ route('department.statistics.index', $department['id']) }}"
               class="{{request()->routeIs('statistics.*') ? '-m-3 p-3 flex items-start text-white bg-gray-100 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800' : '-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="ml-4">
                    <p class="text-base font-medium text-gray-900 dark:text-gray-200 capitalize">
                        <span class="text-orange-applus uppercase">{{$department['id']}}</span> {{__('Statistics')}}
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-white">
                        {{$department['name']}}
                    </p>
                </div>
            </a>
        @endforeach

    </div>
</div>
