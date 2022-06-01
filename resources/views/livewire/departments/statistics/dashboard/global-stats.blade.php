<div>
    <dl class="grid grid-cols-1 rounded-b-lg bg-white overflow-hidden shadow divide-y divide-gray-200 md:grid-cols-2 md:divide-y-0 md:divide-x">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">Active Users <span class="text-xs"></span></dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-orange-applus">
                    {{$totalActiveUsers}}
                    <span class="ml-2 text-sm font-medium text-gray-500"> from {{$totalActiveUsersLastWeek}} {{__('Users')}}</span>
                </div>
                @if( $this->compareValue($totalActiveUsersLastWeek, $totalActiveUsers) > 0)
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-up -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only"> Increased by </span>
                        {{ $this->compareValue($totalActiveUsersLastWeek, $totalActiveUsers) }}%
                    </div>
                @else
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                        <svg  class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                        <span class="sr-only"> Decresed by </span>
                        {{ $this->compareValue($totalActiveUsersLastWeek, $totalActiveUsers) }}%
                    </div>
                @endif
            </dd>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">Avg. Occupation Rooms Rate</dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-orange-applus">
                    {{$this->weekOccupation}}%
                    <span class="ml-2 text-sm font-medium text-gray-500"> from {{$this->lastWeekOccupation}}%</span>
                </div>

                @if( $this->compareValue($lastWeekOccupation, $weekOccupation) > 0)
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                        <!-- Heroicon name: solid/arrow-sm-up -->
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only"> Increased by </span>
                        {{ $this->compareValue($lastWeekOccupation, $weekOccupation) }}%
                    </div>
                @else
                    <div
                        class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                        <svg  class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                        <span class="sr-only"> Decresed by </span>
                        {{ $this->compareValue($lastWeekOccupation, $weekOccupation) }}%
                    </div>
                @endif
            </dd>
        </div>
    </dl>
</div>
