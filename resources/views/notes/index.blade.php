<x-app-layout>
    <x-slot name="header">
        　Note
    </x-slot>
    
    <body>
        <h1>Note</h1>
        <div class="notes">
            <a href="{{ route('notes.create') }}">ノートを書く</a>
            @foreach($notes as $note)
                @if ($note->public === 1)
                    <h1>公開</h1>
                @else
                    <h1>非公開</h1>
                @endif
                <p>{{ $note->created_at }}</p>
                <a href="/notes/{{ $note->id }}">{{ $note->title }}<a>
                <div class="testaments">
                    @foreach ($note->testaments as $testament)
                        <h3>{{ $testament->text }}</h3>
                        <p>{{ $testament->volume->title }} {{ $testament->chapter }}:{{ $testament->section }}</p>
                    @endforeach
                </div>
            @endforeach
        </div>
        <pre><code>{{ var_dump($notes) }}</code></pre>
    </body>
</x-app-layout>