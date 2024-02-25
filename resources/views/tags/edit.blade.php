<x-app-layout>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    <form action="/tags/{{ $tag->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="tag">
                            <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-1/6" type="text" name="tag[tag]" value="{{ $tag->tag }}">
                            <p class="tag__error" style="color:red">{{ $errors->first('tag.tag') }}</p>
                        </div>
                        <div class="h-3"></div>
                        <div class="flex justify-start">
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <input type="submit" value="保存する"/>
                            </div>
                            <div class="mx-2"></div>
                            <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <a href="{{ route('tags.index') }}">戻る</a>
                            </div>
                        </div>
                    </form>
                </div>    
            </div>
        </div>
    </body>
</x-app-layout>