<x-app-layout>
    <body>
        <div class="testaments">
            <div class="py-12">
                <div class="flex items-center justify-between">
                    <nav class="text-sm sm:text-base bg-white p-4 md:p-6 lg:p-6">
                        <ol class="list-none p-0 inline-flex space-x-2">
                          <li class="flex items-center">
                            <svg onclick="location.href='/testaments'" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" class="cursor-pointer hover:fill-blue-500 transition-colors duration-300" fill="#4b5563"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>        <span class="mx-2">/</span>
                          </li>
                          <li class="flex items-center">
                            <a href="/testaments/volume{{ $volume }}" class="text-gray-600 hover:text-blue-500 transition-colors duration-300">Volume {{ $volume }}</a>
                            <span class="mx-2">/</span>
                          </li>
                          <li class="flex items-center">
                            <span class="text-gray-600 hover:text-blue-500 transition-colors duration-300">
                                <a href="/testaments/volume{{ $volume }}/chapter{{ $chapter }}">Chapter {{ $chapter }}</a>
                            </span>
                          </li>
                        </ol>
                     </nav>
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <a href="#" id="sendData">選択した聖句からノートを作成する</a>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-6 text-gray-900">
                        <div class="mx-auto max-w-2xl lg:mx-0">
                          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $chapter_set->volume->title }}: 第{{ $chapter_set->chapter }}章</h2>
                          <div class="mt-2 text-lg leading-8 text-gray-600">
                              @foreach ($testaments as $testament)
                                 <label>
                                    <input type="checkbox" value={{ $testament->id }} name="ids[]" {{ $testament_id->contains($testament->id) ? 'checked' : '' }}>
                                        <span>
                                            <small>{{ $testament->section }}</small> {{ $testament->text }}
                                        </span>
                                 <label>
                              @endforeach
                          </div>
                        </div>  
                    </div>
                    
                </div>
            </div>
        </div>
        <!--現時点のchapterの値に応じて、前後のページに移動するメカニズム -->
        <div class="pagination">
            <div class="fixed bottom-2 left-0 right-0 p-4 flex justify-between items-center">
                @if ($volume == 1 and $chapter == 1)
                    <p></p>
                @elseif ($earliest_chapter->chapter_id === $testament->chapter)
                    <a href="/testaments/volume{{ $volume - 1 }}/chapter{{ $previous_volume_latest_chapter->chapter_id }}">
                        <!-- 左側のページ送りボタン -->
                        <button class="bg-gray-800 text-gray-200 px-4 py-2 rounded-full">←</button>
                    </a>
                @else
                    <a href="/testaments/volume{{ $volume }}/chapter{{ $testament->chapter - 1 }}">
                        <!-- 左側のページ送りボタン -->
                        <button class="bg-gray-800 text-gray-200 px-4 py-2 rounded-full">←</button>
                    </a>
                @endif
                
                @if ($volume === 66 and $testament->chapter === 22)
                    <p></p>
                @elseif ($latest_chapter->chapter_id === $testament->chapter)
                    <a href="/testaments/volume{{ $volume + 1 }}/chapter1">
                        <!-- 右側のページ送りボタン -->
                        <button class="bg-gray-800 text-gray-200 px-4 py-2 rounded-full">→</button>
                    </a>
                @else
                    <a href="/testaments/volume{{ $volume }}/chapter{{ $testament->chapter + 1 }}">
                        <!-- 右側のページ送りボタン -->
                        <button class="bg-gray-800 text-gray-200 px-4 py-2 rounded-full">→</button>
                    </a>
                @endif
            </div>
        </div>
    </body>
    <script>
        // aタグをクリックした際の処理
        document.getElementById('sendData').addEventListener('click', function(e) {
            e.preventDefault(); // デフォルトのリンク挙動を無効化
        
            // 選択されたチェックボックスの値を収集
            var selectedTestaments = document.querySelectorAll('input[name="ids[]"]:checked');
            var values = [];
            selectedTestaments.forEach(function(testament) {
                values.push(testament.value);
            });
        
            // チェックボックスが1つ以上選択されている場合
            if (values.length > 0) {
                // クエリパラメータを作成
                var queryString = 'ids[]=' + values.join('&ids[]=');
        
                // 判定したいnote_idがあるかどうかをチェック
                @if (isset($edit_note_id))
                    var note_id = {{ $edit_note_id }};
                    var url = '/notes/' + note_id + '/edit?' + queryString;
                @elseif (isset($comment_create_note_id))
                    var note_id = {{ $comment_create_note_id }};
                    var url = '/notes/' + note_id + '/comments?' + queryString;
                @elseif (isset($comment_edit_ids))
                    var note_id = {{ $comment_edit_ids[0] }};
                    var comment_id = {{ $comment_edit_ids[1] }};
                    var url = '/notes/' + note_id + '/comments/' + comment_id + '/edit?' + queryString;
                @else
                    var url = '/notes/create?' + queryString;
                @endif
        
                // GETリクエストを実行
                window.location.href = url;
            } else {
                alert('少なくとも1つのアイテムを選択してください');
            }
        });
    </script>
</x-app-layout>