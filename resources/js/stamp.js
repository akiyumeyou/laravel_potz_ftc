function calculateYPosition(canvas, position, size) {
    const height = canvas.height;
    switch (position) {
        case "top":
            return 20 + parseInt(size);  // テキストサイズを考慮したオフセットを追加
        case "center":
            return (height / 2) + (parseInt(size) / 2);  // 中央に配置するための調整
        case "bottom":
            return height - 20;  // 下部に配置し、オフセットを引く
        default:
            return 20 + parseInt(size);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('preview-canvas');
    const ctx = canvas.getContext('2d');
    const imageInput = document.getElementById('image');
    const dropArea = document.getElementById('drop-area');
    const fileNameContainer = document.getElementById('file-name');
    let img = null;

    // 画像ファイルが選択された時の処理
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                img = new Image();
                img.onload = function() {
                    // 画像を320x440ピクセルにリサイズして描画
                    canvas.width = 320;
                    canvas.height = 440;
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
            fileNameContainer.textContent = "選択されたファイル: " + file.name;
        } else {
            fileNameContainer.textContent = "選択されたファイルは画像ではありません。";
        }
    });

    // ドラッグアンドドロップイベントの設定
    ['dragover', 'dragenter', 'dragleave', 'dragend', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, function(e) {
            e.preventDefault();
            if (['dragover', 'dragenter'].includes(eventName)) {
                dropArea.classList.add('active');
            } else {
                dropArea.classList.remove('active');
            }

            if (eventName === 'drop' && e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                const event = new Event('change');
                imageInput.dispatchEvent(event);
            }
        }, false);
    });

    // クリックでファイル選択
    dropArea.addEventListener('click', function() {
        imageInput.click();
    });

    // テキスト反映ボタンが押された時の処理
    document.getElementById('apply-text').addEventListener('click', function() {
        if (!img) {
            alert("先に画像を選択してください。");
            return;
        }
        const text = document.getElementById('text-overlay').value;
        const color = document.getElementById('text-color').value;
        const position = document.getElementById('text-position').value;
        const size = document.getElementById('text-size').value;

        ctx.clearRect(0, 0, canvas.width, canvas.height); // 以前の描画をクリア
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height); // 画像を再描画
        ctx.font = `${size}px Arial`;
        ctx.fillStyle = color;
        ctx.textAlign = 'center';

        const yPos = calculateYPosition(canvas, position, size);
        ctx.fillText(text, canvas.width / 2, yPos);
    });

    // フォーム送信時にキャンバスの内容を画像として送信
    document.getElementById('stamp-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!img) {
            alert("先に画像を選択してください。");
            return;
        }

        const dataURL = canvas.toDataURL('image/png');
        const blobBin = atob(dataURL.split(',')[1]);
        const array = [];
        for (let i = 0; i < blobBin.length; i++) {
            array.push(blobBin.charCodeAt(i));
        }
        const file = new Blob([new Uint8Array(array)], {type: 'image/png'});
        const formData = new FormData(this);
        formData.append('image', file, 'stamp.png');

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('スタンプが作成されました。');
                window.location.reload();
            } else {
                alert('スタンプの作成に失敗しました。');
            }
        })
        .catch(error => {
            console.error('エラーが発生しました:', error);
            alert('スタンプの作成に失敗しました。');
        });
    });
});
