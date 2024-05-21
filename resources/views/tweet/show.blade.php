<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Message Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>Message Details</h1>
                <p><strong>{{ $tweet->user_name }}:</strong></p>
                @if ($tweet->message_type == 'image')
                    <img src="{{ $tweet->content }}" alt="Image" class="max-w-full h-auto">
                @elseif ($tweet->message_type == 'video')
                    <video controls class="max-w-full h-auto">
                        <source src="{{ $tweet->content }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @elseif ($tweet->message_type == 'link')
                    <a href="{{ $tweet->content }}" target="_blank" class="text-blue-500 hover:text-blue-700">{{ $tweet->content }}</a>
                @else
                    <p>{{ $tweet->content }}</p>
                @endif

                <div class="mt-4">
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
                </div>
                <a href="{{ route('tweets.index') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                    Back to Messages
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

