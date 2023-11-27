<x-app-layout>
    <x-slot name="header">
        　Testament
    </x-slot>
    
    <body>
        <h1>Testament</h1>
        <div class='testaments'>
            @foreach($testaments as $testament)
                <div class='testament'>
                    <h2 class='title'>{{ $testament->volume->title }}</h2>
                    <h3 class='chapter'>第{{ $testament->chapter }}章</h3>
                    <p>{{ $testament->text }}</p>
                </div>
            @endforeach
        </div>
    </body>
</x-app-layout>