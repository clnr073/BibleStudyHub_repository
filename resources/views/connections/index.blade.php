<x-app-layout>
    <body>
        <div class="py-12">
            <p>友達一覧</p>
            @foreach ($friends as $friend)
                <form action="/connections/unfriend" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="friend_id" value="{{ $friend->id }}">
                    {{ $friend->name }}
                    <div class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <input type="submit" value="友達登録を解除"/>
                    </div>
                </form>
            @endforeach
            <p>以下のユーザーから友達リクエストが来ています</p>
            @foreach ($followers as $follower)
                <form action="/connections/approval" method="POST">
                    @csrf
                    <input type="hidden" name="connections_id" value="{{ $follower->id }}">
                    {{ $follower->followedBy->name }}
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <input type="submit" value="友達リクエストを承認"/>
                    </div>
                </form>
            @endforeach
            <p>ユーザー一覧</p>
            @foreach ($users as $user)
                <form action="/connections/follow" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    {{ $user->name }}
                    <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <input type="submit" value="友達リクエストを送信する"/>
                    </div>
                </form>
            @endforeach
        </div>
    </body>
</x-app-layout>