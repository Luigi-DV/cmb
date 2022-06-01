<div class="text-black justify-self-end">
    <label for="select-department" class="text-sm capitalize">{{__('Department')}}</label>
    <select wire:change="changeDepartment" wire:model="department" id="select-department" class="w-60 form-select form-select-sm text-xs appearance-none block p-2 text-sm font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0
                    focus:text-gray-700 focus:bg-white focus:border-orange-applus focus:outline-none focus:ring-transparent uppercase" aria-label=".form-select-sm">
        @foreach($departments as $value)
            <option class="uppercase" value="{{$value}}">{{$value}}</option>
        @endforeach
    </select>
</div>
