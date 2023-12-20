<x-app-layout>
    <x-slot name="header">
        　Note Create
    </x-slot>
    
    <body>
        <div class="py-12">
            <div class="footer">
                <a href="{{ route('notes.index') }}">戻る</a>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form action="/notes" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label>
                                <input type="radio" value="1" name="note[public]" {{ $public_value == true ? 'checked' : '' }}>
                                公開ノート
                            </label>
                            <label>
                                <input type="radio" value="0" name="note[public]" {{ $public_value == false ? 'checked' : '' }}>
                                非公開ノート
                            </label>
                            <br>
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
                                        <input type="checkbox" value={{ $tag->id }} name="tags_array[]" {{ in_array($tag->id, old('tags_array', [])) ? 'checked' : '' }}>
                                            {{ $tag->tag }}
                                    </lavel>
                                @endforeach
                            </div>
                            <div class="image">
                                <input type="file" name="image">
                                <p class="image__error" style="color:red">{{ $errors->first('image') }}</p>
                            </div>
                            <input type="submit" value="保存する"/>
                            <a href="/notes?cancel_notetake=true">キャンセル</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- デバックステップ: oldヘルパーの動作確認 -->
        <pre><code>{{ var_dump(session()->get('_old_input')) }}</code></pre>
        @foreach($session_testaments_data as $key => $value)
            <p>{{ $key }}: {{ $value }}</p>
        @endforeach
        {{ var_dump($all_session_data) }}
    </body>
</x-app-layout>