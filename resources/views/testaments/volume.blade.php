<x-app-layout>
    <x-slot name="header">
        　Testament Index
    </x-slot>
    
    <body>
        <div class="testaments">
            <div class="py-12">
                <a href="/testaments">Home</a> > <span>Volume {{ $volume }}</span>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @foreach ($chapters as $chapter)
                                <a href="/testaments/volume{{ $volume }}/chapter{{ $chapter }}">Chapter {{ $chapter }}</a>
                            @endforeach
                            {{ dump($chapters)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>