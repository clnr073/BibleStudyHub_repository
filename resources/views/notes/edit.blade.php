<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form action="/notes/{{ $note->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center justify-between">
                        <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block" name="note[public]">
                            <option value="1" {{ $public_value == true ? 'selected' : '' }}>公開ノート</option>
                            <option value="0" {{ $public_value == false ? 'selected' : '' }}>非公開ノート</option>
                        </select>
                        <div>
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <input type="submit" value="変更を保存する"/>
                            </div>
                            <div class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <a href="/notes?cancel_note_take=true">変更をキャンセル</a>
                            </div>
                        </div>
                    </div>
                    <br>
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
                    <div class="title">
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-2/3" type="text" name="note[title]" placeholder="タイトル" value="{{ $note->title }}">
                        <p class="title__error" style="color:red">{{ $errors->first('note.title') }}</p>
                    </div>
                    <div class="text">
                        <textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full h-screen" name="note[text]" placeholder="ここにノートを入力">{{ $note->text }}</textarea>
                        <p class="text__error" style="color:red">{{ $errors->first('note.text') }}</p>
                    </div>
                    <div class="tag">
                        <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block" name="tags_array[]" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $tag_id->contains($tag->id) ? 'selected' : '' }}>
                                    {{ $tag->tag }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="image">
                        @if ($note->image_url)
                        <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900">現在登録されている写真:</p>
                        <ul class="mt-3 gap-4 md:gap-6 xl:gap-8 w-1/2">
                            <li class="group flex justify-center items-center bg-gray-100 overflow-hidden rounded-lg shadow-lg relative">
                                <img src="{{ $note->image_url }}" class="object-contain object-center group-hover:scale-105 transition duration-200"/>
                            </li>
                        </ul>
                        <div class="py-1"></div>
                        @endif
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block" type="file" name="image">
                        <p class="image__error" style="color:red">{{ $errors->first('image') }}</p>
                    </div>
                </form>
            </div>
        </div>
    </body>
</x-app-layout>