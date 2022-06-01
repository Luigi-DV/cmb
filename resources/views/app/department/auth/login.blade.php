<x-guest-layout>
    <div class="min-h-screen flex">
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6  lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <x-ui.application-logo />
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Sign in to
                        <span class="text-orange-applus uppercase"> {{$department}} </span></h2>
                </div>
                <div class="mt-8">
                    <div class="mt-6">
                        <form action="{{url('departments/'.$department.'/www/process/login.php')}}" method="POST" class="space-y-6">
                            <div>
                                <label for="login" class="block text-sm font-medium text-gray-700"> Username </label>
                                <div class="mt-1">
                                    <input id="login" name="login" type="text" autocomplete="username" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-transparent focus:ring-none sm:text-sm" autofocus>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label for="password" class="block text-sm font-medium text-gray-700"> Password </label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-transparent focus:ring-none sm:text-sm">
                                </div>
                            </div>
                            @if(isset($_GET['message']) && $_GET['message'] === 'error_bad_login' )
                                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">
                                                {{ __('Please check your credentials') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!--Custom Options
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <label for="remember-me" class="ml-2 block text-sm text-gray-900"> Remember me </label>
                                </div>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-orange-600 hover:text-orange-500"> Forgot your password? </a>
                                </div>
                            </div>
                            -->
                            <div>
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black-applus hover:bg-orange-applus focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="{{asset('images/photo-1505904267569-f02eaeb45a4c.avif')}}" alt="">
        </div>
    </div>
</x-guest-layout>
