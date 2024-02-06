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
                            <div class="edit">
                                <a href="/notes/{{ $note->id }}/edit">編集する</a>
                            </div>
                        </div>
                        <div class="mx-2"></div>
                        <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <div class="footer">
                                <a href="{{ route('notes.index') }}">ノート一覧に戻る</a>
                            </div>
                        </div>
                    </div>
                    <div class="note">
                        <p>{{ $note->created_at }}</p>
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
                    <div class="comments">
                        @foreach ($note->comments as $comment)
                            <p>{{ $comment->text }}</p>
                            @foreach ($comment->testaments as $testament)
                                <p>{{ $testament->text }}</p>
                            @endforeach
                        @endforeach
                        <a href='/notes/{{ $note->id }}/comments'>コメント一覧を表示する</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>