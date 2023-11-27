<x-app-layout>
    <x-slot name="header">
        　Note
    </x-slot>
    
    <body>
        <h1>Note</h1>
        <div class='testaments'>
            @foreach($notes as $note)
                <div class='notes'>
                    <h2>{{ $note->title }}</h2>
                    @foreach ($note->testaments as $testament)
                        <h3>{{ $testament->text }}</h3>
                        <p>{{ $testament->volume->title }} {{ $testament->chapter }}:{{ $testament->section }}</p>
                    @endforeach
                    <p>{{ $note->text }}</p>
                </div>
            @endforeach
        </div>
    </body>
</x-app-layout>