<div>
    <!--Emits [getDateFrom,getDateTo] -->
    <div class="container mx-auto p-5 bg-gray-100 mb-5 shadow-lg rounded-md">
        <livewire:dates.date-range-picker />
    </div>
    <div class="container mx-auto p-5 mb-5 shadow-lg rounded-md">
        <div class="flow-root p-5 rounded-xl">
            <ul role="list" class="-mb-8">
                @forelse(array_slice($audition, 0, $this->perPage) as $audit )
                    <li>
                        <div class="relative pb-8">
                            @if($loop->iteration !== $loop->count)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                            @switch($audit->type)
                                @case('C')
                                <!--User Connected-->
                                    <div>
                                            <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-gray-100">
                                                <svg class="h-5 w-5 text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">User Connection
                                                <a href="#" class="font-bold text-black text-regular">{{ ($this->getUserById($audit->user_modif))->nom }}</a></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>
                                @break
                                @case('D')
                                <!--User Disconnected-->
                                    <div>
                                            <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-gray-100">
                                                <svg class="h-5 w-5 text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">User Disconnection
                                                <a href="#" class="font-bold text-black text-regular">{{ ($this->getUserById($audit->user_modif))->nom }}</a></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>
                                @break
                                @case('AU')
                                <!--Added User-->
                                    <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-gray-100">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">Add User
                                                <span class="font-bold text-black text-regular">{{$audit->informations}}</span>
                                                <a href="#" class="italic font-medium text-gray-900">
                                                    made by {{ ($this->getUserById($audit->user_modif))->nom }}</a></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>
                                @break
                                @case('MP')
                                <!--Project Modification-->
                                    <div>
                                          <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                          </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">
                                                Project Modified
                                                (<span class="text-sm">
                                                        @if($audit->informations)
                                                        {{ $audit->informations }}
                                                    @else
                                                        {{ ($this->getProjectById($audit->project_id))->nom }}
                                                    @endif
                                                    </span>)
                                            </p>
                                            <p class="mt-2">
                                            @if($audit->nouvelles_valeurs)
                                                @if($audit->nouvelles_valeurs !== "null")
                                                    @php
                                                        $audit->nouvelles_valeurs = (array) $audit->nouvelles_valeurs;
                                                        $obj = json_decode($audit->nouvelles_valeurs[0]);
                                                    @endphp
                                                    <ul class="list-disc list-inside capitalize text-sm">
                                                        @foreach($obj as $key => $item)

                                                            <li>
                                                                @php
                                                                    $keyArray = explode("_", $key)
                                                                @endphp
                                                                @foreach($keyArray as $piece)
                                                                    {{$piece}}
                                                                @endforeach
                                                                :
                                                                @if($key === 'createur_id')
                                                                    {{ ($this->getUserById($item))->nom }}
                                                                @else
                                                                    {{$item}}
                                                                @endif

                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else

                                                @endif
                                            @endif
                                            <p class="text-sm font-medium text-gray-900">
                                                made by
                                                <a href="#" class="italic text-orange-applus">{{ ($this->getUserById($audit->user_modif))->nom }}</a>
                                            </p>
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="2020-09-20">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>
                                @break
                                @case('DP')

                                @break
                                @case('MU')
                                <!--Modified User-->
                                    <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-gray-100">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                    </div>
                                @break
                                @case('MT')
                                <!--Modified Task-->
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-gray-100">
                                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">Task Modified</p>
                                            <p>
                                            @if($audit->nouvelles_valeurs)
                                                @if($audit->nouvelles_valeurs !== "null")
                                                    @php
                                                        $audit->nouvelles_valeurs = (array) $audit->nouvelles_valeurs;
                                                        $obj = json_decode($audit->nouvelles_valeurs[0]);
                                                    @endphp
                                                    <ul class="list-disc list-inside capitalize text-sm">
                                                        @foreach($obj as $key => $item)

                                                            <li>
                                                                @php
                                                                    $keyArray = explode("_", $key)
                                                                @endphp
                                                                @foreach($keyArray as $piece)
                                                                    {{$piece}}
                                                                @endforeach:
                                                                <!--Key-->
                                                                @switch($key)
                                                                    @case('createur_id')
                                                                    @php
                                                                        $self = explode(",", $item)
                                                                    @endphp
                                                                    {{  $self[0]  }}
                                                                    @break
                                                                    @case('lieu')
                                                                    @php
                                                                        $self = explode("_", $item)
                                                                    @endphp
                                                                    {{  print_r($self)  }}
                                                                    @break
                                                                @endswitch
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                    @endif
                                                    </p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        made by
                                                        <a href="#" class="italic text-orange-applus">{{ ($this->getUserById($audit->user_modif))->nom }}</a>
                                                    </p>

                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>

                                @break
                                @case('AT')
                                <!--Added Task-->
                                    <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-gray-100">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">Task added</p>
                                            <p class="text-sm font-medium text-gray-900">Info: {{ $audit->informations }} </p>
                                            <p class="text-sm font-medium text-gray-900">
                                                made by
                                                <a href="#" class="italic text-orange-applus">{{ ($this->getUserById($audit->user_modif))->nom }}</a>
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="2020-09-20">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>
                                @break
                                @case('DT')
                                <!--Deleted Task-->
                                    <div>
                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-gray-100">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-regular text-gray-900">Task deleted</p>
                                            <p class="text-sm">
                                                @if($audit->anciennes_valeurs)
                                                    @if($audit->anciennes_valeurs !== "null")
                                                        @php
                                                            $audit->anciennes_valeurs = (array) $audit->anciennes_valeurs;
                                                            $obj = json_decode($audit->anciennes_valeurs[0]);
                                                        @endphp
                                                        ID:{{ $obj->periode_id }}
                                                    @endif
                                                @endif
                                            </p>
                                            <p class="text-sm font-medium text-gray-900">
                                                made by:
                                                <a href="#" class="italic text-orange-applus">{{ ($this->getUserById($audit->user_modif))->nom }}</a>
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="2020-09-20">{{\Carbon\Carbon::parse($audit->date_modif)->toDayDateTimeString()}}</time>
                                        </div>
                                    </div>
                                    @break
                                @endswitch
                            </div>
                        </div>
                    </li>
                @empty
                    @php
                        $showLoading = false;
                    @endphp
                    <li>
                        <div class="relative pb-8">
                            <div class="flex w-full items-center justify-center space-x-3">
                                <span class="mx-3">Empty</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-orange-applus h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
    @if($showLoading)
        <div class="flex w-full justify-center text-orange-applus py-10">
            <p class="animate-pulse text-orange-applus font-semibold mr-2">
                Loading More
            </p>
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-orange-applus" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    @endif
</div>
<script type="text/javascript">
    window.onscroll = function(ev) {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            window.livewire.emit('load-more');
        }
    };
</script>


