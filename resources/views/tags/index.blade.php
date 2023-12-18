<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>

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
                        <p>{{ $tag->tag }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>