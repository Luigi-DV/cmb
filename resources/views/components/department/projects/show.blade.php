<div class="mt-5 border-t border-gray-200">
    <dl class="divide-y divide-gray-200">
        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-base font-medium text-gray-500">{{__('Project ID')}}</dt>
            <dd class="mt-1 flex text-base text-gray-900 sm:mt-0 sm:col-span-2">
                <span class="flex-grow">{{ $project->projet_id }}</span>
            </dd>
        </div>
        <div class="py-4 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
            <dt class="text-base font-medium text-gray-500">{{__('Project Name')}}</dt>
            <dd class="mt-1 flex text-base text-gray-900 sm:mt-0 sm:col-span-2">
                <span class="flex-grow">{{ $project->nom }}</span>
            </dd>
        </div>
        <div class="py-4 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
            <dt class="text-base font-medium text-gray-500">{{__('Project Group')}}</dt>
            <dd class="mt-1 flex text-base text-gray-900 sm:mt-0 sm:col-span-2">
                <span class="flex-grow">{{ $getGroupByID($project->groupe_id)->nom }}</span>
            </dd>
        </div>
        <div class="py-4 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
            <dt class="text-base font-medium text-gray-500">{{__('Payment Status')}}</dt>
            <dd class="mt-1 flex items-center text-base text-gray-900 sm:mt-0 sm:col-span-2">
                <!--Payment Status-->
                <span class="flex-grow">
                    @if($project->statut_bill)
                        @switch($project->statut_bill)
                            @case('po')
                                <span
                                    class="bg-green-500 flex-shrink-0 inline-block h-2 w-2 rounded-full" aria-hidden="true">
                                </span>
                                <span class="mx-2">{{__('With Payment Order')}} [PO]</span>
                                @break
                            @case('n_pr')
                                <span
                                    class="bg-yellow-500 flex-shrink-0 inline-block h-2 w-2 rounded-full" aria-hidden="true">
                                </span>
                                <span class="mx-2">{{__('No Productive')}} [N_PR]</span>
                                @break
                            @case('n_po')
                                <span
                                    class="bg-red-500 flex-shrink-0 inline-block h-2 w-2 rounded-full" aria-hidden="true">
                                </span>
                                <span class="mx-2">{{__('Without Payment Order')}} [WPO]</span>
                                @break
                        @endswitch
                    @else
                        <span class="bg-gray-400 flex-shrink-0 inline-block h-2 w-2 rounded-full" aria-hidden="true">+
                            {{__('Payment status not found')}}
                        </span>
                    @endif
                </span>
                <!--End Payment Status-->
            </dd>
        </div>
        <div class="py-4 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
            <dt class="text-base font-medium text-gray-500">{{__('Project Manager')}}</dt>
            <dd class="mt-1 flex text-base text-gray-900 sm:mt-0 sm:col-span-2">
                <span class="flex-grow">
                @if($project->pm_id && $getUserByID($project->pm_id))
                        {{$getUserByID($project->pm_id)->nom}}
                    @else
                        <span class="text-red-700">{{__('No Project Manager have been found')}}</span>
                    @endif
                </span>
            </dd>
        </div>
        <div class="py-4 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
            <dt class="text-base font-medium text-gray-500">{{__('Creator')}}</dt>
            <dd class="mt-1 flex text-base text-gray-900 sm:mt-0 sm:col-span-2">
                <span class="flex-grow">
                @if($project->createur_id && $getUserByID($project->createur_id))
                        {{$getUserByID($project->createur_id)->nom}}
                @else
                    {{__('No creator have been found')}}
                @endif
                </span>
            </dd>
        </div>
    </dl>
</div>
