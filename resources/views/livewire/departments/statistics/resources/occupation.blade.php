<div class="w-full">
    <div class="rounded-md border-gray-100 shadow-xl">
        <div class="grid grid-cols-2 gap-4 items-center bg-orange-applus rounded-t-lg p-5">
            <h1 class="text-xl text-white font-bold">
                Occupation
            </h1>
        </div>
        <div class="p-5">
            <div class="mb-4">
                <!--Emits [getDateFrom,getDateTo] -->
                <livewire:dates.date-range-picker/>
            </div>
            <div class="py-2 border-t-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="search" class="text-sm">Search</label>
                    <input id="search" wire:model="search" type="search" placeholder="{{__('Resource Name')}}"
                           class="form-control block w-full p-2 text-sm font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-orange-applus focus:ring-orange-applus focus:outline-none">
                </div>

                <div class="w-full md:w-1/2 justify-self-end">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="w-full">
                            <label for="select-results" class="text-sm">Results</label>
                            <select wire:model="results" id="select-results" class="form-select form-select-sm appearance-none block w-full p-2 text-sm font-normal text-gray-700 bg-white bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0
                            focus:text-gray-700 focus:bg-white focus:border-orange-applus focus:outline-none focus:ring-transparent uppercase" aria-label=".form-select-sm example">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="1000">All</option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="select-order" class="text-sm">Order</label>
                            <select wire:model="order" id="select-order" class="form-select form-select-sm appearance-none block w-full p-2 text-sm font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0
                        focus:text-gray-700 focus:bg-white focus:border-orange-applus focus:outline-none focus:ring-transparent uppercase" aria-label=".form-select-sm example">
                                <option value="ASC">Asc</option>
                                <option value="DESC">Desc</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-5 overflow-x-auto">
                <table class="py-20 rounded-md border-collapse bg-white dark:bg-gray-900 table-auto w-full text-sm">
                    <thead class="py-5">
                    <tr class="py-20">
                        <th class="border-b dark:border-slate-600 font-medium py-5 pl-8 text-slate-400 dark:text-slate-200 text-left">Rooms</th>
                        @if($this->getWeeks() <= 1)
                            <th class="border-b dark:border-slate-600 font-medium py-5 pl-8 text-slate-400 dark:text-slate-200 text-left">Week {{$this->getWeek($this->dateTo)}}</th>
                        @else
                            @foreach($this->getPeriod($dateFrom,$dateTo) as $date)
                                @if($loop->index !== 0 &&
                                        $this->getWeek($this->period[$loop->index]) !== $this->getWeek($this->period[$loop->index - 1]))
                                    <th class="border-b dark:border-slate-600 font-medium py-5 px-8 pl-8 text-slate-400 dark:text-slate-200 text-left">Week {{$this->getWeek($date)}}</th>
                                @endif
                            @endforeach
                        @endif
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900">
                    @foreach(array_slice($rooms,0,($this->results)) as $room)
                        <tr x-data="{show : false}" @mouseover.away = "show = false">
                            <td class="border-b border-gray-100 dark:border-gray-700 p-4 pl-8 text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    <span style="background-color: {{'#'.$room->couleur}}" class="border-2 border-gray-200 mr-3 rounded-xl w-3 h-10"></span>
                                    {{$room->nom}}
                                </div>
                            </td>
                            @foreach($this->getPeriod($this->dateFrom , $this->dateTo) as $date)
                                @if($loop->count > 1)
                                    @if($loop->index !==0)
                                        @if($this->getWeek($this->period[$loop->index]) !== $this->getWeek($this->period[$loop->index - 1]))
                                            <td @mouseover="show = true" class="border-b border-gray-100 dark:border-gray-700 p-4 pl-8 text-gray-500 dark:text-gray-400 cursor-pointer">
                                                {{$this->getRoomOccupations($room->user_id, $date)}}%
                                            </td>
                                        @elseif($this->getWeek($this->period[$loop->first]) === $this->getWeek($this->period[$loop->count - 1]))
                                            <td @mouseover="show = true" class="border-b border-gray-100 dark:border-gray-700 p-4 pl-8 text-gray-500 dark:text-gray-400 cursor-pointer">
                                                {{$this->getRoomOccupations($room->user_id, $date)}}%
                                            </td>
                                            @break
                                        @endif
                                    @endif
                                @else
                                    <td @mouseover="show = true" class="border-b border-gray-100 dark:border-gray-700 p-4 pl-8 text-gray-500 dark:text-gray-400 cursor-pointer">
                                        {{$this->getRoomOccupations($room->user_id, $date)}}%
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
