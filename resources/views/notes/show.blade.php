<x-app-layout>
    <x-slot name="header">
        　Note
    </x-slot>
    
    <body>
        <h1>Note</h1>
        <div class="notes">
            @if ($note->public === 1)
                <h1>公開</h1>
            @else
                <h1>非公開</h1>
            @endif
            <p>{{ $note->created_at }}</p>
            <h2>{{ $note->title }}</h2>
                <div class="testaments">
                    @foreach ($note->testaments as $testament)
                        <h3>{{ $testament->text }}</h3>
                        <p>{{ $testament->volume->title }} {{ $testament->chapter }}:{{ $testament->section }}</p>
                    @endforeach
                </div>
                <div class="tags">
                    @foreach ($note->tags as $tag)
                        <p>{{ $tag->tag }}</p>
                    @endforeach
                </div>
                <div class="footer">
                    <a href="{{ route('notes.index') }}">戻る</a>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>