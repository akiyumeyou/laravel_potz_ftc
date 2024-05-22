<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AudioController extends Controller
{
    public function transcribe(Request $request)
    {
        // バリデーション
        $request->validate([
            'audio' => 'required|mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm|max:25000', // 最大25MB
        ]);

        // 音声ファイルを取得
        $audio = $request->file('audio');

        // Guzzleクライアントの初期化
        $client = new Client();

        // OpenAI APIキーの取得
        $apiKey = env('OPENAI_API_KEY');

        // APIリクエストの送信
        $response = $client->post('https://api.openai.com/v1/audio/transcriptions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'multipart/form-data',
            ],
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($audio->getPathname(), 'r'),
                    'filename' => $audio->getClientOriginalName(),
                ],
                [
                    'name'     => 'model',
                    'contents' => 'whisper-1',
                ],
            ],
        ]);

        // レスポンスの処理
        $data = json_decode($response->getBody(), true);

        // テキストを返す
        return response()->json([
            'transcription' => $data['text'] ?? 'Transcription failed.',
        ]);
    }
}
