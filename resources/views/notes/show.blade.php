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
            <div class="comments">
                @foreach ($note->comments as $comment)
                    <p>{{ $comment->text }}</p>
                    @foreach ($comment->testaments as $c_testament)
                        <p>{{ $c_testament->text }}</p>
                    @endforeach
                @endforeach
            </div>
            <div class="new_comment">
                <p>新しいコメントを追加</p>
                <form action="/notes/{{ $note->id }}" method="POST">
                    @csrf
                    <!-- hiddenフィールドとしてnote_idを追加 -->
                    <input type="hidden" name="new_comment[note_id]" value="{{ $note->id }}">
                    <input type="text" name="new_comment[text]" placeholder="ここに入力" value="{{ old('new_comment.text')}}">
                    <p class="title__error" style="color:red">{{ $errors->first('new_comment.text') }}</p>
                    <p>聖句を追加</p>
                    @foreach ($comment_testaments as $comment_testament)
                        <lavel>
                            <input type="checkbox" value={{ $comment_testament->id }} name="testaments_array[]" {{ in_array($comment_testament->id, old('testaments_array', [])) ? 'checked' : '' }}>
                                {{ $comment_testament->text }}
                        </lavel>
                        <br>
                    @endforeach
                    <input type="submit" value="投稿"/>
                </form>
            </div>
            <div class="edit">
                <a href="/notes/{{ $note->id }}/edit">編集する</a>
            </div>
            <div class="footer">
                <a href="{{ route('notes.index') }}">戻る</a>
            </div>
        </div>
        <!-- デバックステップ -->
        {{ dump($note) }}
    </body>
</x-app-layout>