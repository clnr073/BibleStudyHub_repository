<header class="bg-gray-800 text-gray-200">
    <div class="container mx-auto p-3">
        <div class="flex items-center justify-between">
            <a class="text-lg hover:text-gray-300" href="{{ route('dashboard') }}">
                {{ config('app.name') }}
            </a>
            <div>
                @if (Auth::check())
                <x-dropdown>
                    <x-slot name="trigger">
                        <svg class="h-6 w-6 text-white"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('testaments.index')" :active="request()->routeIs('testaments.index')">
                            {{ __('聖書を読む') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('notes.index')" :active="request()->routeIs('notes.index')">
                            {{ __('ノート') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('tags.index')" :active="request()->routeIs('tags.index')">
                            {{ __('タグ') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('connections.index')" :active="request()->routeIs('connections.index')">
                            {{ __('友達') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('プロフィール') }}
                        </x-dropdown-link>
                       <x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left">ログアウト</button>
                            </form>
                       </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
                @else
                <a class="text-sm hover:text-gray-400" href="{{ route('login') }}">ログイン</a>
                <a class="text-sm hover:text-gray-400 ml-4" href="{{ route('register') }}">会員登録</a>
                @endif
            </div>
        </div>
    </div>
</header>