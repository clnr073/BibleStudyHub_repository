<x-app-layout>
    <body>
        <div class="py-12">
            <div class="notes">
                <div class="flex items-center justify-between">
                    <div class="tags flex flex-wrap">
                        <a href="/notes"><span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">すべてのノート</span></a>
                        @foreach ($tags as $tag)
                            <a href="/notes?tag={{ $tag->id }}">
                                <span class="bg-red-200 text-red-500 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{ $tag->tag }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <a href="{{ route('create') }}">ノートを書く</a>
                    </div>
                </div>
                <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($notes as $note)
                        <div class="p-3">
                            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow">
                                <div class="w-full h-36 flex justify-center items-center bg-gray-200">
                                    <img src="{{ $note->image_url }}" class="max-w-full max-h-full">
                                </div>
                                <div class="p-5 max-w-full">
                                    <div class="flex flex-wrap items-center">
                                        <div>
                                            @if ($note->public === 1)
                                                <span class="bg-gray-200 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">公開</span>
                                            @else
                                                <span class="bg-gray-200 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">非公開</span>
                                            @endif
                                        </div>
                                    
                                        @foreach ($note->tags as $tag)
                                            <a href="/notes?tag={{ $tag->id }}">
                                                <span class="bg-red-200 text-red-500 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{ $tag->tag }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="flex py-1">
                                        <div class="w-full">
                                            <div class="flex justify-between items-center">
                                                <a href="/notes/{{ $note->id }}" class="w-2/3">
                                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 truncate overflow-hidden">
                                                        {{ $note->title }}
                                                    </h5>
                                                </a>
                                                <div class="ml-auto flex items-center">
                                                    <div>
                                                        <a href='/notes/{{ $note->id }}/comments'>
                                                            <button class="py-4 px-1 relative border-2 border-transparent text-gray-800 rounded-full hover:text-gray-400 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out" aria-label="Cart">
                                                              <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.6 8.5h8m-8 3.5H12m7.1-7H5c-.2 0-.5 0-.6.3-.2.1-.3.3-.3.6V15c0 .3 0 .5.3.6.1.2.4.3.6.3h4l3 4 3-4h4.1c.2 0 .5 0 .6-.3.2-.1.3-.3.3-.6V6c0-.3 0-.5-.3-.6a.9.9 0 0 0-.6-.3Z"/>
                                                              </svg>
                                                              @if ($note->comments->count() !== 0)
                                                              <span class="absolute inset-0 object-right-top -mr-6">
                                                                <div class="inline-flex items-center px-1.5 py-0.5 border-2 border-white rounded-full text-xs font-semibold leading-4 bg-red-500 text-white">
                                                                  {{ $note->comments->count() }}
                                                                </div>
                                                              </span>
                                                              @endif
                                                            </button>
                                                        </a>
                                                    </div>
                                                    <div>
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
                                                                @can ('update', $note)
                                                                <x-dropdown-link href="/notes/{{ $note->id }}/edit">編集する</x-dropdown-link>
                                                                <x-dropdown-link>
                                                                    <form action="/notes/{{ $note->id }}" id="form_{{ $note->id }}" method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" onclick="deleteNote({{ $note->id }})">このノートを削除する</button>
                                                                    </form>
                                                                </x-dropdown-link>
                                                                @endcan
                                                            </x-slot>
                                                        </x-dropdown>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">{{ $note->user->name }}・{{ $note->created_at }}</p>
                                    <p class="mb-3 font-normal text-gray-700 h-[50px] overflow-hidden line-clamp-3">{{ $note->text }}</p>
                                    <a href="/notes/{{ $note->id }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        読む
                                         <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
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