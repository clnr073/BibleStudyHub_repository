<x-app-layout>
    <x-slot name="header">
        　Note Edit
    </x-slot>
    
    <body>
        <h1>Note Edit</h1>
        <form action="/notes/{{ $note_id }}/comments/{{ $comment->id }}" method="POST">
            @csrf
            @method('PUT')
            <!-- hiddenフィールドとしてnote_idを追加 -->
            <input type="hidden" name="new_comment[note_id]" value="{{ $note_id }}">
            <input type="text" name="new_comment[text]" placeholder="ここに入力" value="{{ $comment->text }}">
            <p class="title__error" style="color:red">{{ $errors->first('new_comment.text') }}</p>
            <div class="testaments">
                @foreach ($testaments as $testament)
                    <input type="hidden" name="testaments_array[]" value="{{ $testament->id }}">
                    <p>{{ $testament->text }}</p>
                @endforeach
                @if (count($testaments) === 0 or !$last_selected_testament)
                <a href="/testaments">聖句を追加</a>
                @else
                <a href="/testaments/volume{{ $last_selected_testament->volume->id }}/chapter{{ $last_selected_testament->chapter }}">聖句を追加</a>
                @endif
                <br>
            </div>
            <input type="submit" value="保存する"/>
            <a href="/notes/{{ $note_id }}/comments?cancel_comment_take=true">変更をキャンセル</a>
        </form>
        <div class="footer">
            <a href="/notes/{{ $note_id }}/comments">戻る</a>
        </div>
    </body>
</x-app-layout>