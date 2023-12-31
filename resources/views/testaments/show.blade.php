<x-app-layout>
    <x-slot name="header">
        　Testament Show
    </x-slot>
    
    <body>
        <div class="testaments">
            <div class="py-12">
                <a href="/testaments">Home</a> > <a href="/testaments/volume{{ $volume }}">Volume {{ $volume }}</a> > <span>Chapter {{ $chapter }}</span>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <a href="#" id="sendData">選択した聖句からノートを作成する</a>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h2>{{ $chapter_set->volume->title }}: 第{{ $chapter_set->chapter }}章</h2>
                                @foreach ($testaments as $testament)
                                     <label>
                                        <input type="checkbox" value={{ $testament->id }} name="ids[]" {{ $testament_id->contains($testament->id) ? 'checked' : '' }}>
                                            <span>
                                                <small>{{ $testament->section }}</small> {{ $testament->text }}
                                            </span>
                                     <label>
                                @endforeach
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--現時点のchapterの値に応じて、前後のページに移動するメカニズム -->
        <div class="pagination">
            <br>
            @if ($volume == 1 and $chapter == 1)
                <p></p>
            @elseif ($earliest_chapter->chapter_id === $testament->chapter)
                <a href="/testaments/volume{{ $volume - 1 }}/chapter{{ $previous_volume_latest_chapter->chapter_id }}">←</a>
            @else
                <a href="/testaments/volume{{ $volume }}/chapter{{ $testament->chapter - 1 }}">←</a>
            @endif
            
            @if ($volume === 66 and $testament->chapter === 21)
                <p></p>
            @elseif ($latest_chapter->chapter_id === $testament->chapter)
                <a href="/testaments/volume{{ $volume + 1 }}/chapter1">→</a>
            @else
                <a href="/testaments/volume{{ $volume }}/chapter{{ $testament->chapter + 1 }}">→</a>
            @endif
        </div>
        {{ var_dump($all_session_data) }}
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