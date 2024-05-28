<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Family Tail Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>マイクボタンで音声入力</h1>
                <div id="conversation-history" class="bg-gray-100 p-4 mb-4 rounded overflow-x-auto" style="height: 50%; white-space: nowrap;"></div>
                <form id="chatForm">
                    @csrf
                    <label for="content"></label>
                    <textarea name="content" id="content" class="form-textarea mt-1 block w-full"></textarea>

                    <button type="button" id="start-record-btn" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                        🎤 音声入力
                    </button>
                    <br>

                    <button type="button" id="send-btn" class="mt-4 inline-block bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded">
                        送信
                    </button> 
                </form>
                <div id="transcription-feedback" class="mt-4 text-green-500"></div>
                <input type="hidden" id="transcribe-route" value="{{ route('transcribe') }}">
                <input type="hidden" id="chat-route" value="{{ route('chat') }}">
                <input type="hidden" id="user-name" value="{{ Auth::user()->name }}">
                <div id="response" class="mt-4"></div>
                <div id="conversation" class="mt-4"></div>
            </div>
        </div>
    </div>

    @vite('resources/js/chat.js')
</x-app-layout>
