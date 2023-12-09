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
            <p>聖句を追加</p>
            @foreach ($testaments as $testament)
                <lavel>
                    <input type="checkbox" value={{ $testament->id }} name="testaments_array[]" {{ $testament_id->contains($testament->id) ? 'checked' : '' }}>
                        {{ $testament->text }}
                </lavel>
                <br>
            @endforeach
            <input type="submit" value="保存する"/>
        </form>
        <div class="footer">
            <a href="/notes/{{ $note_id }}/comments">戻る</a>
        </div>
    </body>
</x-app-layout>