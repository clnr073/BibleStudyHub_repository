<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <p class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $note->title }}</p>
                        <div class="mx-2"></div>
                        <div class="tags flex flex-wrap">
                            @foreach ($note->tags as $tag)
                                <span class="mr-2 mb-2">
                                    <div class="space-x-4">
                                        <div class="inline-block relative py-1 text-xs">
                                            <div class="absolute inset-0 text-red-200 flex">
                                                <svg height="100%" viewBox="0 0 50 100">
                                                    <path
                                                        d="M49.9,0a17.1,17.1,0,0,0-12,5L5,37.9A17,17,0,0,0,5,62L37.9,94.9a17.1,17.1,0,0,0,12,5ZM25.4,59.4a9.5,9.5,0,1,1,9.5-9.5A9.5,9.5,0,0,1,25.4,59.4Z"
                                                        fill="currentColor" />
                                                </svg>
                                                <div class="flex-grow h-full -ml-px bg-red-200 rounded-md rounded-l-nonse"></div>
                                            </div>
                                            <span class="relative text-red-500 uppercase font-semibold pr-px">
                                                <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/notes?tag={{ $tag->id }}">{{ $tag->tag }}</a><span>&nbsp;</span>
                                            </span>
                                        </div>
                                    </div>
                                </span>
                            @endforeach
                        </div>
                        <div class="flex-grow"></div>
                        <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <div class="footer">
                                <a href="{{ route('notes.index') }}">ノート一覧に戻る</a>
                            </div>
                        </div>
                        <div class="mx-2"></div>
                        <x-dropdown align="light">
                            <x-slot name="trigger" class="relative z-60">
                                <button>
                                    <svg class="h-6 w-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link href="/notes/{{ $note->id }}/comments">コメントする</x-dropdown-link>
                                @if ($note->user_id === $user_id)
                                <x-dropdown-link href="/notes/{{ $note->id }}/edit">編集する</x-dropdown-link>
                                <x-dropdown-link>
                                    <form action="/notes/{{ $note->id }}" id="form_{{ $note->id }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="deleteNote({{ $note->id }})">このノートを削除する</button>
                                    </form>
                                </x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="note">
                        <p>{{ $note->user->name }}・{{ $note->created_at }}</p>
                        @if ($note->public === 1)
                            <h1>公開ノート</h1>
                        @else
                            <h1>非公開ノート</h1>
                        @endif
                        @if ($testaments_by_volume_and_chapter && count($testaments_by_volume_and_chapter) > 0)
                        <div class="testaments">
                            <blockquote class="p-4 my-4 border-s-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-800">
                                @foreach ($testaments_by_volume_and_chapter as $volume_id => $chapters)
                                    @foreach ($chapters as $chapter => $testaments)
                                        @foreach ($testaments as $testament)
                                            <p class="italic font-medium leading-relaxed text-gray-900 dark:text-white">{{ $testament->text }}</p>
                                        @endforeach
                                        <p>{{ $testament->volume->title }} {{ $chapter }}:{{ $testaments->first()->section }}-{{ $testaments->last()->section }}</p>
                                        <br>
                                    @endforeach
                                @endforeach
                            </blockquote>
                        </div>
                        @endif
                        <p>{{ $note->text }}</p>
                        @if ($note->image_url)
                        <div class="image">
                            <img src="{{ $note->image_url }}" alt="画像が読み込めません。"/>
                        </div>
                        @endif
                    </div>
                    <a href='/notes/{{ $note->id }}/comments'>コメント一覧を表示する</a>
                </div>
            </div>
        </div>
    </body>
    <script>
        function deleteNote(id) {
            'use strict'
            
            if (confirm('削除すると復元できません。本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</x-app-layout>