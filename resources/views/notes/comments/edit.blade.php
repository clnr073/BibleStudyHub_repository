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
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-5/6" type="text" name="new_comment[text]" placeholder="ここに入力" value="{{ $comment->text }}">
                        <p class="title__error" style="color:red">{{ $errors->first('new_comment.text') }}</p>
                        <div class="testaments">
                            @foreach ($testaments as $testament)
                                <input type="hidden" name="testaments_array[]" value="{{ $testament->id }}">
                            @endforeach
                            @if ($testaments_by_volume_and_chapter && count($testaments_by_volume_and_chapter) > 0)
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
                            @endif
                            @if (count($testaments) === 0 or !$last_selected_testament)
                            <a class="border-gray-500 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-24" href="/testaments">聖句を追加</a>
                            @else
                            <a href="/testaments/volume{{ $last_selected_testament->volume->id }}/chapter{{ $last_selected_testament->chapter }}">聖句を追加</a>
                            @endif
                            <br>
                        </div>
                        <input type="submit" value="保存する"/>
                        <a href="/notes/{{ $note_id }}/comments?cancel_comment_take=true">変更をキャンセル</a>
                    </form>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>