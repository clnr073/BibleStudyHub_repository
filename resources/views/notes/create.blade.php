<x-app-layout>
    <x-slot name="header">
        　Note
    </x-slot>
    
    <body>
        <h1>Note</h1>
        <form action="/notes" method="POST">
            @csrf
            <div class='title'>
                <h2>タイトル</h2>
                <input type="text" name="note[title]">
            </div>
            <div class='text'>
                <h2>本文</h2>
                <textarea name="note[text]"></textarea>
            </div>
            <input type="submit" value="保存する"/>
        </form>
        <div class="footer">
            <a href="{{ route('notes.index') }}">戻る</a>
        </div>
    </body>
</x-app-layout>