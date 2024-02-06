<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form action="/tags/{{ $tag->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="tag">
                            <input type="text" name="tag[tag]" value="{{ $tag->tag }}">
                            <p class="tag__error" style="color:red">{{ $errors->first('tag.tag') }}</p>
                        </div>
                        <input type="submit" value="保存する"/>
                    </form>
                    <div class="footer">
                        <a href="{{ route('tags.index') }}">戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>