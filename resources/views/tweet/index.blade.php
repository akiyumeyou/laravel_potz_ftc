<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Famiry Tail Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>Famiry Tail Chat</h1>
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
                        <form method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="send_wrap">
                                <fieldset>
                                    <label>投稿：{{ Auth::user()->name }}</label><br>
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
                                    <input type="text" name="content" class="chat_input">
                                    <input type="hidden" name="message_type" value="text">
                                    <button id="send" type="submit"><img src="{{ asset('storage/img/btn_send.png') }}" width="50" height="50"></button>
                                    <input type="file" name="image" accept="image/*">
                                </fieldset>
                            </div>
                            <div id="image-gallery" class="gallery">
                                @foreach ($images as $image)
                                    <img src="{{ asset($image->image) }}" alt="Image" class="stamp-image">
                                @endforeach
                            </div>
                        </form>
                    </div>

                    <button id="stampbt" onclick="location.href='{{ route('stamp.create') }}'">スタンプ作成</button>
                    <button id="stamsend">スタンプ送る</button>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('tweets.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
        メッセージを送る
    </a>
</x-app-layout>

<!-- <script src="{{ asset('js/tweet.js') }}"></script> -->
@vite('resources/js/tweet.js')
