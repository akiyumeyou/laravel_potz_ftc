<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>川柳投稿</title>
    <style>
        body {
            font-size: 20px;
            background-color: #FFFAF0; /* 薄いオレンジ色 */
            margin: 0;
            padding-top: 80px;
            padding-bottom: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .senryu-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            writing-mode: vertical-rl; /* 縦書き */
            text-orientation: upright;
            margin-top: 20px;
        }

        .senryu-input {
            width: 100%; /* 入力枠を大きくする */
            padding: 10px; /* 内側の余白を増やす */
            margin-bottom: 10px; /* 下側の余白を増やす */
            box-sizing: border-box; /* paddingを含めたサイズでwidthを計算 */
            font-size: 38px;
            writing-mode: vertical-rl;
            text-orientation: upright;
        }

        #drop-area {
            width: 80%; /* ドロップエリアを少し小さくする */
            height: 300px;
            margin: auto; /* 中央揃え */
            padding: 5px;
            font-size: 20px;
            border: 2px dashed #f2c487; /* 点線のスタイル */
            text-align: center;
            position: relative;
        }

        #drop-area:hover {
            background-color: #e9b013;
        }

        .file-input-label {
            color: blue;
            text-decoration: underline;
        }

        #file-name {
            margin-top: 10px;
        }

        .toukou_btn {
            background-color: green;
            font-size: 30px; /* フォントサイズを大きく */
            color: rgb(241, 249, 243);
            padding: 20px 20px; /* スマートフォンでタップしやすいサイズに */
            border-radius: 5px;
            display: block; /* ボタンをブロックレベル要素として扱う */
            margin: 0 auto; /* 左右中央揃え */
            cursor: pointer;
        }

        .toukou_btn:hover {
            background-color: #e9b013; /* ホバー色を変更 */
        }

        .preview {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 100%;
            max-height: 100%;
        }

        @media (max-width: 768px) {
            .senryu-text, .iine {
                font-size: 36px;
            }
            .fieldset {
                max-width: 100%; /* コンテンツの最大幅を制限 */
                margin: auto; /* 中央寄せ */
            }
            #drop-area {
                height: 200px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let dropArea = document.getElementById('drop-area');
            let fileElem = document.getElementById('fileElem');
            let fileNameDisplay = document.getElementById('file-name');
            let previewContainer = document.getElementById('preview-container');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.classList.add('highlight');
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.classList.remove('highlight');
            });

            // Handle dropped files
            dropArea.addEventListener('drop', handleDrop, false);
            fileElem.addEventListener('change', handleFiles);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function handleDrop(e) {
                let dt = e.dataTransfer;
                let files = dt.files;

                handleFiles(files);
            }

            function handleFiles(files) {
                files = [...files];
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                fileElem.files = dataTransfer.files;
                fileNameDisplay.innerText = files.map(file => file.name).join(', ');
                previewFile(files[0]);
            }

            function previewFile(file) {
                while (previewContainer.firstChild) {
                    previewContainer.removeChild(previewContainer.firstChild);
                }

                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    let media;
                    if (file.type.startsWith('image/')) {
                        media = document.createElement('img');
                    } else if (file.type.startsWith('video/')) {
                        media = document.createElement('video');
                        media.controls = true;
                    }
                    media.src = reader.result;
                    media.classList.add('preview');
                    previewContainer.appendChild(media);
                }
            }
        });
    </script>
</head>
<body>
    <header>
        <nav>
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('最初のページ') }}
            </x-nav-link>
            <x-nav-link :href="route('senryus.index')" :active="request()->routeIs('senryus.index')">
                {{ __('シルバー川柳') }}
            </x-nav-link>
        </nav>
    </header>

    <div>
        @if (session('success'))
            <div>{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif

        <form action="{{ route('senryus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="senryu-container">
                <label for="theme">テーマ:</label>
                <input type="text" name="theme" id="theme" class="senryu-input" required><br>
                <label for="s_text1">上五:</label>
                <input type="text" name="s_text1" id="s_text1" class="senryu-input" required maxlength="5"><br>
                <label for="s_text2">中七:</label>
                <input type="text" name="s_text2" id="s_text2" class="senryu-input" required maxlength="7"><br>
                <label for="s_text3">下五:</label>
                <input type="text" name="s_text3" id="s_text3" class="senryu-input" required maxlength="5"><br>
            </div>
            
            <div id="drop-area">
                <p>ここにファイルをドラッグ＆ドロップ</p>
                <input type="file" name="img_path" id="fileElem" class="file-input" accept="image/*,video/*" style="display:none">
                <label class="file-input-label" for="fileElem">またはファイルを選択</label>
                <p id="file-name"></p>
                <div id="preview-container"></div>
            </div>

            <button type="submit" class="toukou_btn">投稿する</button>
        </form>
    </div>

    <footer>
        <p>© 2024 川柳アプリ</p>
    </footer>
</body>
</html>
