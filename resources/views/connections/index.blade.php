<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    @if (!empty($friends))
                    <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900">友達一覧:</p>
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
                        <div class="py-1"></div>
                    @endforeach
                    @endif
                    <div class='paginate'>
                        {{ $friends->links() }}
                    </div>
                    <br>
                    @if (!empty($followers))
                        <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900">以下のユーザーから友達リクエストが来ています:</p>
                        @foreach ($followers as $follower)
                            <form action="/connections/approval" method="POST">
                                @csrf
                                <input type="hidden" name="connections_id" value="{{ $follower->id }}">
                                {{ $follower->followedBy->name }}
                                <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <input type="submit" value="友達リクエストを承認"/>
                                </div>
                            </form>
                            <div class="py-1"></div>
                        @endforeach
                    @endif
                    <div class='paginate'>
                        {{ $followers->links() }}
                    </div>
                    <br>
                    <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900">ユーザー一覧:</p>
                    @foreach ($users as $user)
                        @if (!$follows->contains($user->id))
                        <form action="/connections/follow" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            {{ $user->name }}
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <input type="submit" value="友達リクエストを送信する"/>
                            </div>
                        </form>
                        @else
                        {{ $user->name }}
                        <div class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            リクエスト送信済
                        </div>
                        @endif
                        <div class="py-1"></div>
                    @endforeach
                    <div class='paginate'>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>