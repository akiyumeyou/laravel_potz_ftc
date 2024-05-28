<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Family Tail Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1>入力またはファイルを選択してください</h1>
                <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data" id="tweetForm">
                    @csrf
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-textarea mt-1 block w-full"></textarea>

                    <button type="button" id="start-record-btn" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                        🎤 音声入力
                    </button>
                    <br>

                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-input mt-1 block w-full">

                    <label for="audio">Audio</label>
                    <input type="file" name="audio" id="audio" class="form-input mt-1 block w-full">

                    <button type="submit" class="mt-4 inline-block bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded">
                        送信
                    </button> 
                </form>
                <a href="{{ route('tweets.index') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    戻る
                </a>
                <div id="transcription-feedback" class="mt-4 text-green-500"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const recordButton = document.getElementById('start-record-btn');
            const contentField = document.getElementById('content');
            const feedback = document.getElementById('transcription-feedback');
            const transcribeRoute = @json(route('transcribe'));

            let mediaRecorder;
            let audioChunks = [];

            recordButton.addEventListener('click', async () => {
                if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                    startRecording();
                } else {
                    stopRecording();
                }
            });

            async function startRecording() {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = event => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });
                    audioChunks = [];
                    const formData = new FormData();
                    formData.append('audio', audioBlob, 'audio.mp3');

                    console.log('Sending request to:', transcribeRoute);
                    console.log('FormData:', formData.get('audio'));

                    try {
                        const response = await fetch(transcribeRoute, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const text = await response.text();
                        console.log('Response text:', text);

                        // JSONパースを試みる前にHTMLエラーページをチェック
                        if (text.startsWith('<!DOCTYPE html>')) {
                            throw new Error('Received HTML response instead of JSON');
                        }

                        const data = JSON.parse(text);
                        console.log('Parsed response:', data);

                        if (data.transcription) {
                            contentField.value = data.transcription;
                            feedback.textContent = 'Transcription successful';
                        } else {
                            feedback.textContent = data.error ? 'Transcription failed: ' + data.error : 'Transcription failed';
                        }
                    } catch (error) {
                        feedback.textContent = 'Transcription request failed: ' + error.message;
                        console.error('Transcription request failed:', error);
                    }
                };

                mediaRecorder.start();
                recordButton.textContent = '⏹ Stop Recording';
                feedback.textContent = 'Recording...';
            }

            function stopRecording() {
                mediaRecorder.stop();
                recordButton.textContent = '🎤 Start Recording';
            }
        });
    </script>
</x-app-layout>