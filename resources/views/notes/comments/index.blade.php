<x-app-layout>
    <x-slot name="header">
        　Note Comment
    </x-slot>
    
    <body>
        <div class="footer">
            <a href="/notes/{{ $note_id }}">戻る</a>
        </div>
        <div class="comments">
            @foreach ($comments as $comment)
                <br>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <p>{{ $comment->created_at }}</p>
                            <p>{{ $comment->text }}</p>
                            @foreach ($comment->testaments as $testament)
                                <p>{{ $testament->text }}</p>
                            @endforeach
                            <a href="/notes/{{ $note_id }}/comments/{{ $comment->id }}/edit">このコメントを編集する</a>
                            <form action="/notes/{{ $note_id }}/comments/{{ $comment->id }}" id="form_{{ $comment->id }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="deleteComment({{ $comment->id }})">このコメントを削除する</button>
                            </form>
                         </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="new_comment">
            <br>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>新しいコメントを追加</p>
                        <form action="/notes/{{ $note_id }}/comments" method="POST">
                            @csrf
                            <!-- hiddenフィールドとしてnote_idを追加 -->
                            <input type="hidden" name="new_comment[note_id]" value="{{ $note_id }}">
                            <input type="text" name="new_comment[text]" placeholder="ここに入力" value="{{ old('new_comment.text') }}">
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
                </div>
            </div>
        </div>
        <script>
            function deleteComment(id) {
                'use strict'
                
                if (confirm('削除すると復元できません。本当に削除しますか？')) {
                    document.getElementById(`form_${id}`).submit();
                }
            }
        </script>
    </body>
</x-app-layout>