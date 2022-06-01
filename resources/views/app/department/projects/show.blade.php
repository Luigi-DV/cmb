<x-app-layout>
    <x-slot name="header">
        <div>
            <nav class="sm:hidden" aria-label="Back">
                <a href="{{route('department.projects.index', $department)}}" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <!-- Heroicon name: solid/chevron-left -->
                    <svg class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>{{__('Back')}}</span>
                </a>
            </nav>
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <a href="{{route('department.index', $department)}}" class="text-sm font-medium text-gray-500 hover:text-gray-700 uppercase">{{$department}}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <!-- Heroicon name: solid/chevron-right -->
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{route('department.projects.index', $department)}}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{__('Projects')}}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <!-- Heroicon name: solid/chevron-right -->
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{route('department.projects.show', [$department, $project->projet_id])}}" aria-current="page" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $project->nom }}</a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-7 text-gray-900 sm:text-3xl sm:truncate">{{ $project->nom }}</h2>
            </div>
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                <form action="{{url('/departments/'.$department.'/www/projets.php')}}" method="POST">
                    <input name="rechercheProjet" value="{{$project->nom}}" type="hidden">
                    <button type="submit" class="ml-3 inline-flex items-center border border-transparent px-4 py-2 bg-orange-applus rounded-md font-medium text-white hover:bg-black-applus focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        {{__('Display in planner')}}</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="flex min-h-screen items-center justify-center">
        <main class="mt-8 pb-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div>
                    <h3 class="text-3xl leading-6 font-medium text-gray-900">Project Information</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Project details and data.</p>
                </div>

                <x-department.projects.show :department="$department" :project="$project"></x-department.projects.show>
            </div>
        </main>
    </div>
</x-app-layout>
