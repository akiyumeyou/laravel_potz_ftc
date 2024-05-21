<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Message') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>Create Message</h1>
                <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="content">Content:</label>
                    <textarea name="content" id="content" class="form-textarea mt-1 block w-full"></textarea>

                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image" class="form-input mt-1 block w-full">

                    <button type="submit" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                        送信
                    </button>
                </form>
                <a href="{{ route('tweets.index') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    チャットに戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>




