<x-app-layout>
    <x-slot name="header">
        　Note
    </x-slot>
    
    <body>
        <h1>Note</h1>
        <form action="/notes" method="POST">
            @csrf
            <div class="testament">
                <label>
                    <input type="radio" value="1" name="note[public]" {{ $value == true ? 'checked' : '' }}>
                    公開ノート
                </label>
                <label>
                    <input type="radio" value="0" name="note[public]" {{ $value == false ? 'checked' : '' }}>
                    非公開ノート
                </label>
                <br>
                <h2>聖句</h2>
                @foreach ($testaments as $testament)
                    <lavel>
                        <input type="checkbox" value={{ $testament->id }} name="testaments_array[]">
                            {{ $testament->text }}
                    </lavel>
                    <br>
                @endforeach
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="note[title]" placeholder="タイトル" value="{{ old('note.title')}}">
                <p class="title__error" style="color:red">{{ $errors->first('note.title') }}</p>
            </div>
            <div class="text">
                <h2>本文</h2>
                <textarea name="note[text]" placeholder="ここにノートを入力">{{ old('note.text')}}</textarea>
                <p class="text__error" style="color:red">{{ $errors->first('note.text') }}</p>
            </div>
            
            <div class="tag">
                <h2>タグ</h2>
                @foreach ($tags as $tag)
                    <lavel>
                        <input type="checkbox" value={{ $tag->id }} name="tags_array[]">
                            {{ $tag->tag }}
                    </lavel>
                @endforeach
            </div>
            <input type="submit" value="保存する"/>
        </form>
        <div class="footer">
            <a href="{{ route('notes.index') }}">戻る</a>
        </div>
    </body>
</x-app-layout>