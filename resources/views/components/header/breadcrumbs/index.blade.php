<div class="flex justify-left items-center px-4 md:px-10 ">
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center py-4 overflow-y-auto whitespace-nowrap">
            <a href="{{ route('department.'.$resources.'.index', $department) }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{ $resources }}</a>
                @php
                    $i = Route::currentRouteName();
                @endphp
                @if($i !== 'department.statistics.index')
                    <span class="mx-5 text-gray-500 dark:text-gray-300">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    @switch($i)
                        @case('department.statistics.resources')
                            <a href="{{ route('statistics.resources') }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{ __('Resources') }}</a>
                            @break
                        @case('department.statistics.projects')
                            <a href="{{ route('statistics.resources') }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{ __('Projects') }}</a>
                            @break
                        @case('department.statistics.audition')
                            <a href="{{ route('statistics.audition') }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{ __('Audition') }}</a>
                            @break
                    @endswitch
                @endif
        </div>
        @if(request()->routeIs('department.statistics.*'))
            <div class="md:justify-self-end">
                <livewire:departments.statistics.forms.change-department :url="url()->current()" />
            </div>
        @endif
    </div>
</div>
