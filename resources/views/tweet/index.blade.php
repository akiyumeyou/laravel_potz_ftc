<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Famiry Tail Chat') }}
        </h2>
    </x-slot>
    <style>
        .stamp-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-start;
        }

        .stamp-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .chat-message.user {
            text-align: right;
            background-color: #f0f0f0;
        }

        .chat-message.other {
            text-align: left;
            background-color: #e0e0e0;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>Famiry Tail Chat</h1>
                <ul id="message-list">
                    @foreach ($messages as $tweet)
                        <li class="mb-4 chat-message {{ Auth::id() == $tweet->user_id ? 'user' : 'other' }}">
                            <strong>{{ $tweet->user_name }}:</strong>
                            @if ($tweet->message_type == 'image')
                                <div>
                                    <img src="{{ asset($tweet->content) }}" alt="Image" class="max-w-full h-auto">
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
                            @elseif ($tweet->message_type == 'stamp')
                                <div>
                                    <img src="{{ asset($tweet->content) }}" alt="Stamp" class="max-w-full h-auto">
                                </div>
                            @else
                                <p>{{ $tweet->content }}</p>
                            @endif

                            <div class="mt-2">
                                @if(Auth::check() && Auth::id() == $tweet->user_id)
                                    <a href="{{ route('tweets.edit', $tweet->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-2 px-4 rounded">
                                        編集
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div id="phone">
                    <div id="screen">
                        <div id="output" class="scroll_bar overflow-y-auto overflow-x-hidden h-96"></div>
                        <form id="tweet-form" method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="send_wrap">
                                <fieldset>
                                    <label>投稿：{{ Auth::user()->name }}</label><br>
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
                                    <input type="text" name="content" id="content" class="chat_input">
                                    <input type="hidden" name="message_type" id="message_type" value="text">
                                    <input type="hidden" name="stamp" id="stamp">
                                    <button id="send" type="submit"><img src="{{ asset('img/btn_send.png') }}" width="50" height="50"></button>
                                    <input type="file" name="image" accept="image/*" id="image">
                                </fieldset>
                            </div>
                        </form>
                        <div id="stamp-gallery" class="stamp-gallery mt-4">
                            @foreach ($images as $image)
                                <img src="{{ asset($image->image) }}" alt="Image" class="stamp-image" onclick="selectStamp('{{ asset($image->image) }}')">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectStamp(imageUrl) {
            document.getElementById('content').value = imageUrl;
            document.getElementById('message_type').value = 'stamp';
            document.getElementById('stamp').value = imageUrl;
            document.getElementById('tweet-form').submit();
        }
    </script>
</x-app-layout>
