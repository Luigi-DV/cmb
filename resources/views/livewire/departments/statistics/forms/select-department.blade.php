<div class="flex items-center justify-center w-full h-screen -mt-20" wire:keydown.enter="submitDepartment">
    <form wire:submit.prevent="submitDepartment">
        <div x-cloak x-data="{show:false}" class="flex flex-col items-center relative ease-in-out duration-150" @click.away="show = false">
            <div class="w-full">
                <label for="department">
                    {{__('Select your department')}}
                </label>
                <div class="my-2 bg-white p-1 flex border border-gray-200 rounded">
                    <div class="flex flex-auto flex-wrap"></div>
                    <input @click="show = true" id="department" placeholder="{{__('Department')}}" wire:model="department" class="p-1 px-2 appearance-none outline-none w-full text-gray-800 uppercase" readonly>
                    <div class="ease-in-out duration-150">
                        @if($department)
                            <button class="cursor-pointer w-6 h-full flex items-center text-gray-400 outline-none focus:outline-none" wire:click="resetSearch()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x w-4 h-4">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div @click="show = !show" class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200" x-transition.duration.200ms>
                        <button class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div x-show="show" class="absolute top-20 shadow z-40 w-full left-0 rounded overflow-y-auto" x-transition.duration.200ms>
                <div class="flex flex-col w-full">
                    @foreach($departments as $value)
                        <div wire:click="setValue({{$loop->index}})"
                             @click="show = false"
                             class="cursor-pointer w-full border-gray-100 rounded-t border-b
                                    hover:bg-gray-100">
                            <div class="flex w-full items-center p-2 pl-2 border-transparent bg-white border-l-2 relative hover:bg-gray-100 hover:text-orange-applus {{ $department == $value ? 'border-orange-applus ' : 'hover:border-orange-applus'}}">
                                <div class="w-full items-center flex">
                                    <div class="mx-2 leading-6 uppercase">{{$departments[$loop->index]}}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @error('department')
            <span class="error text-xs text-red-800">{{ $message }}</span>
            @enderror

            <div x-show="!show" class="mt-2 w-full">
                <button type="submit" class="w-full inline-flex items-center px-4 py-2 bg-orange-applus border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-orange-applus disabled:opacity-25 transition">
                    {{__('Enter')}}
                </button>
            </div>
        </div>
    </form>
    <style>
        #department::placeholder
        {
            text-transform: capitalize;
        }
    </style>
</div>

