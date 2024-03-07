<x-app-layout>
    <body>
        <div class="py-12">
            <form action={{ route('tags.index') }} method="POST">
                <div class="flex items-center justify-between">
                    @csrf
                    <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block" type="text" name="tag[tag]" placeholder="新しいタグ名を入力" value="{{ old('tag.tag') }}">
                    <p class="tag__error" style="color:red">{{ $errors->first('tag.tag') }}</p>
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <input type="submit" value="保存する"/>
                    </div>
                </div>
            </form>
            <div class="grid sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4">
                @foreach ($tags as $tag)
                    <div class="p-3">
                        <div class="p-2 bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg overflow-visible items-center">
                            <div class="px-3">
                                <div class="flex justify-between">
                                    <div class="tags">
                                        <a href="/notes?tag={{ $tag->id }}">{{ $tag->tag }}</a>
                                    </div>
                                    @can ('update', $tag)
                                    <x-dropdown align="light">
                                        <x-slot name="trigger" class="relative z-60">
                                            <button>
                                                <svg class="h-6 w-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link href="/tags/{{ $tag->id }}/edit">編集する</x-dropdown-link>
                                            <x-dropdown-link>
                                                <form action="/tags/{{ $tag->id }}" id="form_{{ $tag->id }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="deleteTag({{ $tag->id }})">削除</button>
                                                </form>
                                            </x-dropdown-link>
                                        </x-slot>
                                    </x-dropdown>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class='paginate'>
                {{ $tags->links() }}
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