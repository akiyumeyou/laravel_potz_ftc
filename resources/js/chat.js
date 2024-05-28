document.addEventListener('DOMContentLoaded', function () {
    const recordButton = document.getElementById('start-record-btn');
    const sendButton = document.getElementById('send-btn');
    const contentField = document.getElementById('content');
    const feedback = document.getElementById('transcription-feedback');
    const transcribeRoute = document.getElementById('transcribe-route').value;
    const chatRoute = document.getElementById('chat-route').value;
    const userName = document.getElementById('user-name').value; // ユーザー名を取得
    const conversationDiv = document.getElementById('conversation');
    const conversationHistory = document.getElementById('conversation-history');

    let mediaRecorder;
    let audioChunks = [];
    let conversation = [];

    // Load initial conversation history
    loadConversationHistory();

    recordButton.addEventListener('click', async () => {
        if (!mediaRecorder || mediaRecorder.state === 'inactive') {
            startRecording();
        } else {
            stopRecording();
        }
    });

    sendButton.addEventListener('click', async () => {
        const text = contentField.value;
        if (text.trim().length === 0) {
            feedback.textContent = 'Please enter or transcribe some text first.';
            return;
        }

        try {
            const chatResponse = await fetch(chatRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ text: text })
            });

            if (!chatResponse.ok) {
                throw new Error(`HTTP error! status: ${chatResponse.status}`);
            }

            const chatData = await chatResponse.json();
            updateConversationHistory({ sender: userName, text: text, timestamp: new Date().toLocaleTimeString() }); // ユーザー名と時間を表示
            updateConversationHistory({ sender: 'AI', text: chatData.response, timestamp: new Date().toLocaleTimeString() });

            contentField.value = ''; // Clear the textarea after sending the message
        } catch (error) {
            feedback.textContent = 'Request failed: ' + error.message;
            console.error('Request failed:', error);
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

            try {
                const response = await fetch(transcribeRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const text = await response.text();

                if (text.startsWith('<!DOCTYPE html>')) {
                    throw new Error('Received HTML response instead of JSON');
                }

                const data = JSON.parse(text);

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

    function updateConversationHistory(entry) {
        conversation.push(entry);
        conversationHistory.innerHTML = conversation.map(entry => {
            return `<div><strong>${entry.sender}</strong> (${entry.timestamp}): ${entry.text}</div>`;
        }).join('');
    }

    async function loadConversationHistory() {
        try {
            const response = await fetch('/conversation-history'); // 正しいエンドポイント
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }
            data.forEach(entry => {
                updateConversationHistory(entry);
            });
        } catch (error) {
            console.error('Failed to load conversation history:', error);
        }
    }
});
