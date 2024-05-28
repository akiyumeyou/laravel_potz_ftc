document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('stamsend').addEventListener('click', function() {
        document.querySelector('.gallery').classList.toggle('show');
    });

    loadMessages();

    document.querySelectorAll('.stamp-image').forEach(function(image) {
        image.addEventListener('click', function() {
            if (confirm('このスタンプを送信しますか？')) {
                sendMessage({
                    user_id: document.querySelector('input[name="user_id"]').value,
                    user_name: document.querySelector('input[name="user_name"]').value,
                    message_type: 'stamp',
                    content: this.src
                });
            }
        });
    });

    document.getElementById('send').addEventListener('click', function(e) {
        e.preventDefault();
        var content = document.querySelector('input[name="content"]').value;
        if (!content) {
            alert('メッセージを入力してください。');
            return;
        }
        sendMessage({
            user_id: document.querySelector('input[name="user_id"]').value,
            user_name: document.querySelector('input[name="user_name"]').value,
            message_type: 'text',
            content: content
        });
    });
});

function sendMessage(data) {
    fetch('/tweets', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('input[name="content"]').value = '';
            loadMessages();
        } else {
            alert('メッセージの送信に失敗しました。');
        }
    })
    .catch(error => {
        console.error('エラーが発生しました:', error);
    });
}

function loadMessages() {
    fetch('/tweets/messages')
    .then(response => response.json())
    .then(data => {
        const output = document.getElementById('output');
        output.innerHTML = '';

        data.forEach(message => {
            var date = new Date(message.timestamp);
            var formattedTime = (date.getMonth() + 1) + '/' + date.getDate() + ' ' +
                                date.getHours() + ':' + 
                                (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());

            var alignmentClass = (message.user_id == currentUserId) ? 'right' : 'left';
            var userClass = (message.user_id == currentUserId) ? 'its_me' : 'not_me';
            var timeAlignmentClass = (message.user_id == currentUserId) ? 'time-right' : 'time-left';
            var readText = ' 母見た';

            var messageHtml;

            if (message.message_type === 'stamp') {
                messageHtml = '<div class="message-wrapper ' + alignmentClass + '">' + 
                              '<div class="user-name">♡: ' + message.user_name + '</div>' +
                              '<img src="' + message.content + '" style="max-width: 380px;">' +
                              '<div class="message-time ' + timeAlignmentClass + '">' + formattedTime + readText + '</div>' +
                              '</div>';
            } else {
                messageHtml = '<div class="message-wrapper ' + alignmentClass + '">' + 
                              '<div class="user-name">♡: ' + message.user_name + '</div>' +
                              '<div class="message-content ' + userClass + '">' + message.content + '</div>' +
                              '<div class="message-time ' + timeAlignmentClass + '">' + formattedTime + readText + '</div>' +
                              '</div>';
            }
            output.insertAdjacentHTML('beforeend', messageHtml);
        });
    })
    .catch(error => {
        alert('メッセージの読み込みに失敗しました。');
        console.error('エラーが発生しました:', error);
    });
}
