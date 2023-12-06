<x-app-layout>
    <x-slot name="header">
        　Note Edit
    </x-slot>
    
    <body>
        <h1>Note Edit</h1>
        <form action="/notes/{{ $note->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="testament">
                <label>
                    <input type="radio" value="1" name="note[public]" {{ $public_value == true ? 'checked' : '' }}>
                    公開ノート
                </label>
                <label>
                    <input type="radio" value="0" name="note[public]" {{ $public_value == false ? 'checked' : '' }}>
                    非公開ノート
                </label>
                <br>
                <h2>聖句</h2>
                @foreach ($testaments as $testament)
                    <lavel>
                        <input type="checkbox" value={{ $testament->id }} name="testaments_array[]" {{ $testament_id->contains($testament->id) ? 'checked' : '' }}>
                            {{ $testament->text }}
                    </lavel>
                    <br>
                @endforeach
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="note[title]" placeholder="タイトル" value="{{ $note->title }}">
                <p class="title__error" style="color:red">{{ $errors->first('note.title') }}</p>
            </div>
            <div class="text">
                <h2>本文</h2>
                <textarea name="note[text]" placeholder="ここにノートを入力">{{ $note->text }}</textarea>
                <p class="text__error" style="color:red">{{ $errors->first('note.text') }}</p>
            </div>
            
            <div class="tag">
                <h2>タグ</h2>
                @foreach ($tags as $tag)
                    <lavel>
                        <input type="checkbox" value={{ $tag->id }} name="tags_array[]" {{ $tag_id->contains($tag->id) ? 'checked' : '' }}>
                            {{ $tag->tag }}
                    </lavel>
                @endforeach
            </div>
            <input type="submit" value="保存する"/>
        </form>
        <div class="footer">
            <a href="{{ route('notes.index') }}">戻る</a>
        </div>
        <!-- デバックステップ -->
        <p>{{ $note->testaments->pluck('id') }}</p>
        {{ dump($note) }}</code></pre>
    </body>
</x-app-layout>