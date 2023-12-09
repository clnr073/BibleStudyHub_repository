<x-app-layout>
    <x-slot name="header">
        　Testament Index
    </x-slot>
    
    <body>
        <div class="testaments">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h2>{{ $chapter_set->volume->title }}: 第{{ $chapter_set->chapter }}章</h2>
                            @foreach ($testaments as $testament)
                                <input type="checkbox" value={{ $testament->id }} name="testaments_array[]">
                                <small>{{ $testament->section }}</small> {{ $testament->text }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--現時点のchapterの値に応じて、前後のページに移動するメカニズム -->
        <div class="pagination">
            <br>
            @if ($testament->volume_id === 1 and $testament->chapter === 1)
                <p></p>
            @elseif ($earliest_chapter->chapter_id === $testament->chapter)
                <a href="/testaments/{{ $testament->volume_id - 1 }}/{{ $previous_volume_latest_chapter->chapter_id }}">←</a>
            @else
                <a href="/testaments/{{ $testament->volume_id }}/{{ $testament->chapter - 1 }}">←</a>
            @endif
            
            @if ($testament->volume_id === 66 and $testament->chapter === 21)
                <p></p>
            @elseif ($latest_chapter->chapter_id === $testament->chapter)
                <a href="/testaments/{{ $testament->volume_id + 1 }}/1">→</a>
            @else
                <a href="/testaments/{{ $testament->volume_id }}/{{ $testament->chapter + 1 }}">→</a>
            @endif
            <br>
        </div>
            {{ $latest_chapter }}
            {{ $earliest_chapter }}
            {{ $previous_volume_latest_chapter }}
            {{ dump($testaments) }}
    </body>
</x-app-layout>