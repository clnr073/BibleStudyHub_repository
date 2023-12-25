<x-app-layout>
    <x-slot name="header">
        　Testament Index
    </x-slot>
    
    <body>
        <div class="testaments">
            <div class="py-12">
                <p>Home</p>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @foreach ($volumes as $volume)
                                <a href="/testaments/volume{{ $volume->id }}">{{ $volume->title }}</a>
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>