<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    <form action="/notes/{{ $note_id }}/comments/{{ $comment->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- hiddenフィールドとしてnote_idを追加 -->
                        <input type="hidden" name="new_comment[note_id]" value="{{ $note_id }}">
                        <textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full h-20 overflow-wrap" name="new_comment[text]" placeholder="ここに入力">{{ $comment->text }}</textarea>
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
                            <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">
                                @if (count($testaments) === 0 or !$last_selected_testament)
                                <a href="/testaments">聖句を追加</a>
                                @else
                                <a href="/testaments/volume{{ $last_selected_testament->volume->id }}/chapter{{ $last_selected_testament->chapter }}">聖句を追加</a>
                                @endif
                            </span>
                            <br>
                        </div>
                        <div class="flex justify-end">
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <input type="submit" value="保存する"/>
                            </div>
                            <div class="mx-2"></div>
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <a href="/notes/{{ $note_id }}/comments?cancel_comment_take=true">変更をキャンセル</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>