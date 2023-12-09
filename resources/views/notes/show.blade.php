<x-app-layout>
    <x-slot name="header">
        　Note Show
    </x-slot>
    
    <body>
        <a href='/notes/{{ $note->id }}/comments'>コメント一覧を表示する</a>
        <div class="edit">
            <a href="/notes/{{ $note->id }}/edit">編集する</a>
        </div>
        <div class="footer">
            <a href="{{ route('notes.index') }}">戻る</a>
        </div>
        <div class="note">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
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
                         </div>
                    </div>
                </div>
            </div>
            <div class="comments">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @foreach ($note->comments as $comment)
                                <p>{{ $comment->text }}</p>
                                @foreach ($comment->testaments as $testament)
                                    <p>{{ $testament->text }}</p>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- デバックステップ -->
        {{ dump($note) }}
    </body>
</x-app-layout>