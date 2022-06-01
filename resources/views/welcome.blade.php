<x-app-layout>
    <div class="min-h-screen dark:bg-gray-900 p-10 md:px-16 md:py-20 w-full grid grid-cols-1 md:grid-cols-2 gap-8 place-content-center place-items-center overflow-hidden">
        <div class="flex w-full md:justify-end items-center">
            <a href="{{url('departments/emc')}}"
               class="group w-full md:w-1/2 py-24 rounded-md border-2 border-orange-applus text-orange-applus font-black text-5xl
               text-center hover:bg-orange-applus hover:text-white">
                {{__('EMC')}}
                <div class="group-hover:scale-150 text-xs font-light mt-5 flex w-full justify-center ease-in-out duration-300">
                    {{__('Electromagnetic compatibility')}}
                </div>
            </a>
        </div>
        <div class="flex w-full md:justify-start items-center">
            <a href="{{url('departments/nvh')}}"
               class="group w-full md:w-1/2 py-24 rounded-md border-2 border-orange-applus text-orange-applus font-black text-5xl
               text-center hover:bg-orange-applus hover:text-white">
                {{__('NVH')}}
                <div class="group-hover:scale-150 text-xs font-light mt-5 flex w-full justify-center ease-in-out duration-300">
                    {{__('Noise, vibration, and harshness')}}
                </div>
            </a>
        </div>

        <div class="flex w-full justify-end items-center">
            <a href="{{url('departments/ele')}}" class="group w-full md:w-1/2 group py-24 rounded-md border-2 border-orange-applus text-orange-applus font-black text-5xl
               text-center hover:bg-orange-applus hover:text-white bg-gray-200 pointer-events-none">
                {{__('ELE')}}
                <div class="group-hover:scale-150 text-xs font-light mt-5 flex w-full justify-center ease-in-out duration-300">
                    {{__('Electronics')}}
                </div>
            </a>
        </div>
        <div class="flex w-full justify-start items-center">
            <a href="{{url('departments/clima')}}" class="group w-full md:w-1/2 py-24 rounded-md border-2 border-orange-applus text-orange-applus font-black text-5xl text-center hover:bg-orange-applus hover:text-white">
                CLIMA
                <div class="group-hover:scale-150 text-xs font-light mt-5 flex w-full justify-center ease-in-out duration-300">
                    {{__('Climatic')}}
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
