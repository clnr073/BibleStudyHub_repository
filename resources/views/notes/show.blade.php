<x-app-layout>
    <x-slot name="header">
        　Note Show
    </x-slot>
    
    <body>
        <h1>Note Show</h1>
        <div class="note">
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
                <p>{{ $note->text }}</p>
                <div class="tags">
                    @foreach ($note->tags as $tag)
                        <p>{{ $tag->tag }}</p>
                    @endforeach
                </div>
                <div class="edit">
                    <a href="/notes/{{ $note->id }}/edit">編集する</a>
                </div>
                <div class="footer">
                    <a href="{{ route('notes.index') }}">戻る</a>
                </div>
            </div>
            <!-- デバックステップ -->
            <pre><code>{{ var_dump($note) }}</code></pre>
        </div>
    </body>
</x-app-layout>