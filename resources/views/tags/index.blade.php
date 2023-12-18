<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>
    
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form action={{ route('tags.index') }} method="POST">
                        @csrf
                        <input type="text" name="tag[tag]" placeholder="新しいタグ名を入力" value="{{ old('tag.tag')}}">
                        <p class="tag__error" style="color:red">{{ $errors->first('tag.tag') }}</p>
                        <input type="submit" value="保存する"/>
                    </form>
                </div>
                @foreach ($tags as $tag)
                    <br>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="tags">
                                <p>{{ $tag->tag }}</p>
                                <form action="/tags/{{ $tag->id }}" id="form_{{ $tag->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="deleteTag({{ $tag->id }})">削除</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </body>
    <script>
        function deleteTag(id) {
            'use strict'
            
            if (confirm('削除すると復元できません。本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</x-app-layout>