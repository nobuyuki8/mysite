<div class="min-h-screen bg-gray-100">

    @include('layouts.navigation')

    @isset($nav)
        <div class="bg-gray-100 border-b">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                {{ $nav }}
            </div>
        </div>
    @endisset

    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="py-6">
        {{ $slot }}
    </main>

</div>