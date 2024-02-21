<x-app-layout>
    <body>
        <div class="py-12">
            <div class="notes">
                <div class="flex items-center justify-between">
                    <div class="tags flex flex-wrap">
                        <span class="mr-2 mb-2">
                            <div class="space-x-4">
                                <div class="inline-block relative py-1 text-xs">
                                    <div class="absolute inset-0 text-red-200 flex">
                                        <svg height="100%" viewBox="0 0 50 100">
                                            <path
                                                d="M49.9,0a17.1,17.1,0,0,0-12,5L5,37.9A17,17,0,0,0,5,62L37.9,94.9a17.1,17.1,0,0,0,12,5ZM25.4,59.4a9.5,9.5,0,1,1,9.5-9.5A9.5,9.5,0,0,1,25.4,59.4Z"
                                                fill="currentColor" />
                                        </svg>
                                        <div class="flex-grow h-full -ml-px bg-red-200 rounded-md rounded-l-none"></div>
                                    </div>
                                    <span class="relative text-red-500 uppercase font-semibold pr-px">
                                        <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/notes">すべて</a><span>&nbsp;</span>
                                    </span>
                                </div>
                            </div>
                        </span>
                        @foreach ($tags as $tag)
                            <span class="mr-2 mb-2">
                                <div class="space-x-4">
                                    <div class="inline-block relative py-1 text-xs">
                                        <div class="absolute inset-0 text-red-200 flex">
                                            <svg height="100%" viewBox="0 0 50 100">
                                                <path
                                                    d="M49.9,0a17.1,17.1,0,0,0-12,5L5,37.9A17,17,0,0,0,5,62L37.9,94.9a17.1,17.1,0,0,0,12,5ZM25.4,59.4a9.5,9.5,0,1,1,9.5-9.5A9.5,9.5,0,0,1,25.4,59.4Z"
                                                    fill="currentColor" />
                                            </svg>
                                            <div class="flex-grow h-full -ml-px bg-red-200 rounded-md rounded-l-none"></div>
                                        </div>
                                        <span class="relative text-red-500 uppercase font-semibold pr-px">
                                            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/notes?tag={{ $tag->id }}">{{ $tag->tag }}</a><span>&nbsp;</span>
                                        </span>
                                    </div>
                                </div>
                            </span>
                        @endforeach
                    </div>
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <a href="{{ route('create') }}">ノートを書く</a>
                    </div>
                </div>
                <div class="grid sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3">
                    @foreach($notes as $note)
                        <div class="p-3">
                            <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg overflow-visible">
                                <div class="flex justify-between py-1">
                                    @if ($note->public === 1)
                                        <h1>公開</h1>
                                    @else
                                        <h1>非公開</h1>
                                    @endif
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
                                                            <div class="flex-grow h-full -ml-px bg-red-200 rounded-md rounded-l-none"></div>
                                                        </div>
                                                        <span class="relative text-red-500 uppercase font-semibold pr-px">
                                                            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/notes?tag={{ $tag->id }}">{{ $tag->tag }}</a><span>&nbsp;</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </span>
                                        @endforeach
                                    </div>
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
                                <p>{{ $note->user->name }}・{{ $note->created_at }}</p>
                                <a href="/notes/{{ $note->id }}">{{ $note->title }}<a>
                                @if($note->image_url)
                                <div class="image">
                                    <img src="{{ $note->image_url }}" alt="画像が読み込めません。"/>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class='paginate'>
                    {{ $notes->links() }}
                </div>
            </div>      
        </div>
        <script>
            function deleteNote(id) {
                'use strict'
                
                if (confirm('削除すると復元できません。本当に削除しますか？')) {
                    document.getElementById(`form_${id}`).submit();
                }
            }
        </script>
    </body>
</x-app-layout>