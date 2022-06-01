<div class="flex justify-left items-center">
    <div class="flex items-center py-4 overflow-y-auto whitespace-nowrap">
        @if($management)
            <a href="{{ route('management.index') }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{__('Management')}}</a>
            <span class="mx-5 text-gray-500 dark:text-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </span>
        @endif
        @if($scheduler)
            <a href="{{ route('scheduler.index') }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{__('Scheduler')}}</a>
            <span class="mx-5 text-gray-500 dark:text-gray-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </span>
        @endif
        <a href="{{ route($resources.'.index') }}" class="text-gray-600 dark:text-gray-200 hover:underline capitalize">{{ $resources }}</a>
        <span class="mx-5 text-gray-500 dark:text-gray-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </span>
        <a href="{{route($resources.'.show', $id)}}" class="text-orange-applus hover:underline capitalize">{{$title}}</a>
    </div>
</div>
