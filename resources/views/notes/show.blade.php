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
                                        <p>{{ $testament->volume->title }} {{ $chapter }}:{{ $section_info_by_volume[$volume_id][$chapter]['first_section'] }}-{{ $section_info_by_volume[$volume_id][$chapter]['last_section'] }}</p>
                                        <br>
                                    @endforeach
                                @endforeach
                                <br>
                            </div>
                            <p>{{ $note->created_at }}</p>
                            <h2>{{ $note->title }}</h2>
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
        {{ dump($testament->chapter) }}
        {{ dump($note) }}
    </body>
</x-app-layout>