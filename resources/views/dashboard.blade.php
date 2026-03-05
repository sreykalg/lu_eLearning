<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-950 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-primary-950">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="mt-1 text-primary-600">Continue learning or explore new courses.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <a href="{{ route('courses.index') }}"
                   class="block p-6 bg-white rounded-xl border border-primary-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="font-semibold text-primary-950">Browse Courses</h3>
                            <p class="text-sm text-primary-600">Explore available courses and start learning</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="block p-6 bg-white rounded-xl border border-primary-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="font-semibold text-primary-950">Profile</h3>
                            <p class="text-sm text-primary-600">Update your account settings</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
