<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <a href="/notes/{{ $note_id }}">ノートに戻る</a>
                    </div>
                    <div class="comments">
                        @foreach ($comments as $comment)
                        <div class="p-3">
                            <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg overflow-visible">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p>
                                            {{ $comment->user->name }}・{{ $comment->created_at }}
                                            @if ($comment->created_at != $comment->updated_at)
                                                (編集済み)
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        @if ($comment->user_id === $user_id)
                                        <x-dropdown align="light">
                                            <x-slot name="trigger" class="relative z-60">
                                                <button>
                                                    <svg class="h-6 w-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <x-dropdown-link href="/notes/{{ $note_id }}/comments/{{ $comment->id }}/edit">編集する</x-dropdown-link>
                                                <x-dropdown-link>
                                                    <form action="/notes/{{ $note_id }}/comments/{{ $comment->id }}" id="form_{{ $comment->id }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="deleteComment({{ $comment->id }})">このコメントを削除する</button>
                                                    </form>
                                                </x-dropdown-link>
                                            </x-slot>
                                        </x-dropdown>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-5">
                                    @if ($comment->grouped_testaments && count($comment->grouped_testaments) > 0)
                                        <blockquote class="p-4 my-4 border-s-4 border-gray-300 bg-gray-50">
                                            @foreach ($comment->grouped_testaments as $volume_id => $chapters)
                                                @foreach ($chapters as $chapter => $testaments)
                                                    @foreach ($testaments as $testament)
                                                        <p class="italic font-medium leading-relaxed text-gray-900">{{ $testament->text }}</p>
                                                    @endforeach
                                                    <div class="h-3"></div>
                                                    @php
                                                        $first_section = $testaments->first()->section;
                                                        $last_section = $testaments->last()->section;
                                                        $section_to_display = $first_section === $last_section ? $first_section : "$first_section-$last_section";
                                                    @endphp
                                                    <p>{{ $testaments->first()->volume->title }} {{ $chapter }}: {{ $section_to_display }}</p>
                                                @endforeach
                                                @if (!$loop->last)
                                                    <br>
                                                @endif
                                            @endforeach
                                        </blockquote>
                                    @endif
                                    <p>{{ $comment->text }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class='paginate'>
                            {{ $comments->links() }}
                        </div>
                    </div>
                    <br>
                    <div class="new_comment">
                        <form action="/notes/{{ $note_id }}/comments" method="POST">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div><p>新しいコメントを追加</p></div>
                            <div class="flex-grow"></div>
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <input type="submit" value="投稿"/>
                            </div>
                            <div class="mx-2"></div>
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <div class="footer">
                                    <a href="/notes/{{ $note_id }}/comments?cancel_comment_take=true">キャンセル</a>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="flex flex-col">
                                <!-- hiddenフィールドとしてnote_idを追加 -->
                                <input type="hidden" name="new_comment[note_id]" value="{{ $note_id }}">
                                <textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full h-20 overflow-wrap" name="new_comment[text]" placeholder="ここに入力">{{ old('new_comment.text')}}</textarea>
                                <p class="title__error" style="color:red">{{ $errors->first('new_comment.text') }}</p>
                                <div class="testaments">
                                    @foreach ($testaments as $testament)
                                        <input type="hidden" name="testaments_array[]" value="{{ $testament->id }}">
                                    @endforeach
                                    @if ($testaments_by_volume_and_chapter && count($testaments_by_volume_and_chapter) > 0)
                                    <blockquote class="p-4 my-4 border-s-4 border-gray-300 bg-gray-50">
                                        @foreach ($testaments_by_volume_and_chapter as $volume_id => $chapters)
                                            @foreach ($chapters as $chapter => $testaments)
                                                @foreach ($testaments as $testament)
                                                    <p class="italic font-medium leading-relaxed text-gray-900">{{ $testament->text }}</p>
                                                @endforeach
                                                <div class="h-3"></div>
                                                <p>{{ $testament->volume->title }} {{ $chapter }}:{{ $testaments->first()->section }}-{{ $testaments->last()->section }}</p>
                                            @endforeach
                                            @if (!$loop->last)
                                                <br>
                                            @endif
                                        @endforeach
                                    </blockquote>
                                    @endif
                                    <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">
                                        @if (count($testaments) === 0 or !$last_selected_testament)
                                        <a href="/testaments?comment_create={{ $note_id }}">聖句を追加</a>
                                        @else
                                        <a href="/testaments/volume{{ $last_selected_testament->volume->id }}/chapter{{ $last_selected_testament->chapter }}">聖句を追加</a>
                                        @endif
                                    </span>
                                    <br>
                                </div>
                            </div>
                        </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        <script>
            function deleteComment(id) {
                'use strict'
                
                if (confirm('削除すると復元できません。本当に削除しますか？')) {
                    document.getElementById(`form_${id}`).submit();
                }
            }
        </script>
    </body>
</x-app-layout>