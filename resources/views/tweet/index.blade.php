<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Famiry Tail Chat') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="public/css/tweet.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>Famiry Tail Chat</h1>
                <a href="{{ route('tweets.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                    メッセージを送る
                </a>
                <ul>
                    @foreach ($messages as $tweet)
                        <li class="mb-4">
                            <strong>{{ $tweet->user_name }}:</strong>
                            @if ($tweet->message_type == 'image')
                                <div>
                                    <img src="{{ $tweet->content }}" alt="Image" class="max-w-full h-auto">
                                </div>
                            @elseif ($tweet->message_type == 'video')
                                <div>
                                    <video controls class="max-w-full h-auto">
                                        <source src="{{ $tweet->content }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @elseif ($tweet->message_type == 'link')
                                <div>
                                    <a href="{{ $tweet->content }}" target="_blank" class="text-blue-500 hover:text-blue-700">{{ $tweet->content }}</a>
                                </div>
                            @else
                                <p>{{ $tweet->content }}</p>
                            @endif

                            <div class="mt-2">
                                @if(Auth::check() && Auth::id() == $tweet->user_id)
                                    <a href="{{ route('tweets.edit', $tweet->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-2 px-4 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('tweets.destroy', $tweet->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-black font-bold py-2 px-4 rounded">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</x-app-layout>
