<x-app-layout>
    <x-slot name="header">
        　Notes
    </x-slot>
    
    <body>
        <div class="notes">
            <a href="{{ route('notes.create') }}">ノートを書く</a>
            @foreach($notes as $note)
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @if ($note->public === 1)
                                <h1>公開</h1>
                            @else
                                <h1>非公開</h1>
                            @endif
                            <p>{{ $note->created_at }}</p>
                            <a href="/notes/{{ $note->id }}">{{ $note->title }}<a>
                            <div class="note_text">
                                <p>{{ $note->text }}</p>
                            </div>
                            <div class="note_tag">
                                @foreach ($note->tags as $tag)
                                    <p>{{ $tag->tag }}</p>
                                @endforeach
                            </div>
                            <div class="delete">
                                <form action="/notes/{{ $note->id }}" id="form_{{ $note->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="deleteNote({{ $note->id }})">このノートを削除する</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            @endforeach
            <!-- デバックステップ -->
            {{ dump($notes) }}
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