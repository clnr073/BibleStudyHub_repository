<x-app-layout>
    <body>
        <div class="py-12">
            <a href='/notes/{{ $note->id }}/comments'>コメント一覧を表示する</a>
            <div class="edit">
                <a href="/notes/{{ $note->id }}/edit">編集する</a>
            </div>
            <div class="footer">
                <a href="{{ route('notes.index') }}">戻る</a>
            </div>
            <div class="note">
                <p>{{ $note->created_at }}</p>
                @if ($note->public === 1)
                    <h1>公開ノート</h1>
                @else
                    <h1>非公開ノート</h1>
                @endif
                <div class="testaments">
                    <br>
                    @foreach ($testaments_by_volume_and_chapter as $volume_id => $chapters)
                        @foreach ($chapters as $chapter => $testaments)
                            @foreach ($testaments as $testament)
                                <p>{{ $testament->text }}</p>
                            @endforeach
                            <p>{{ $testament->volume->title }} {{ $chapter }}:{{ $testaments->first()->section }}-{{ $testaments->last()->section }}</p>
                            <br>
                        @endforeach
                    @endforeach
                    <br>
                </div>
                <h2>{{ $note->title }}</h2>
                <p>{{ $note->text }}</p>
                <div class="tags">
                    @foreach ($note->tags as $tag)
                        <p>{{ $tag->tag }}</p>
                    @endforeach
                </div>
                @if ($note->image_url)
                <div class="image">
                    <img src="{{ $note->image_url }}" alt="画像が読み込めません。"/>
                </div>
                @endif
                </div>
                <div class="comments">
                    @foreach ($note->comments as $comment)
                        <p>{{ $comment->text }}</p>
                        @foreach ($comment->testaments as $testament)
                            <p>{{ $testament->text }}</p>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</x-app-layout>