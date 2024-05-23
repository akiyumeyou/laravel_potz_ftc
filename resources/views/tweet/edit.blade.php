<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Message') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>Edit Message</h1>
                <form action="{{ route('tweets.update', $tweet->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label for="content">Content:</label>
                    <textarea name="content" id="content" class="form-textarea mt-1 block w-full" required>{{ $tweet->content }}</textarea>
                    <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-green font-bold py-2 px-4 rounded">
                        更新
                    </button>
                </form>
                <form action="{{ route('tweets.destroy', $tweet->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-blue font-bold py-2 px-4 rounded">
                        削除
                    </button>
                </form>
                <a href="{{ route('tweets.index') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
