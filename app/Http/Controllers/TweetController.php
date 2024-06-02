<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Stamp; // 追加
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Logファサードをインポート
use GuzzleHttp\Client;

class TweetController extends Controller
{
    public function index()
    {
        $messages = Tweet::all();
        $images = Stamp::all(); // スタンプのデータを取得

        return view('tweet.index', compact('messages', 'images'));
    }

    public function create()
    {
        return view('tweet.create');
    }


    public function getMessages()
    {
        $messages = Tweet::all();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'message_type' => 'required|string',
        ]);

        $tweet = new Tweet();
        $tweet->user_id = Auth::id();
        $tweet->user_name = Auth::user()->name;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $tweet->content = '/storage/' . $imagePath;
            $tweet->message_type = 'image';
        } elseif ($request->input('message_type') === 'stamp') {
            $tweet->content = $request->input('content');
            $tweet->message_type = 'stamp';
        } else {
            $tweet->content = $request->input('content');
            $tweet->message_type = 'text';
        }

        if (empty($tweet->content)) {
            return redirect()->route('tweets.index')->with('error', 'Content cannot be null');
        }

        $tweet->save();

        return redirect()->route('tweets.index')->with('success', 'Tweet created successfully.');
    }

    public function loadMessages()
    {
        $messages = Tweet::all();
        return response()->json($messages);
    }

    public function transcribe(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm|max:25000', // 最大25MB
            ]);

            $audio = $request->file('audio');
            $client = new Client();
            $apiKey = env('OPENAI_API_KEY');

            if (!$apiKey) {
                throw new \Exception('API key is missing.');
            }

            $audioPath = $audio->getPathname();
            $audioName = $audio->getClientOriginalName();

            Log::info('Audio path: ' . $audioPath);
            Log::info('Audio name: ' . $audioName);
            Log::info('API Key: ' . $apiKey);

            // ファイルの内容を取得して変数に格納
            $fileContents = file_get_contents($audioPath);
            if ($fileContents === false) {
                throw new \Exception('Failed to read audio file');
            }

            $response = $client->post('https://api.openai.com/v1/audio/transcriptions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $fileContents,
                        'filename' => $audioName,
                    ],
                    [
                        'name'     => 'model',
                        'contents' => 'whisper-1',
                    ],
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            Log::info('Response status: ' . $statusCode);
            Log::info('Response body: ' . $responseBody);

            if ($statusCode !== 200) {
                throw new \Exception('API request failed with status code ' . $statusCode);
            }

            $data = json_decode($responseBody, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decode error: ' . json_last_error_msg());
            }

            return response()->json([
                'transcription' => $data['text'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Transcription error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Tweet $tweet)
    {
        return view('tweet.show', compact('tweet'));
    }

    public function edit(Tweet $tweet)
    {
        return view('tweet.edit', compact('tweet'));
    }

    public function update(Request $request, Tweet $tweet)
    {
        $request->validate([
            'content' => 'required_without_all:image,video,link',
            'image' => 'nullable|image|max:10240',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
            'link' => 'nullable|url'
        ]);

        $messageType = 'text';
        $content = $request->input('content');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $content = Storage::url($path);
            $messageType = 'image';
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', 'public');
            $content = Storage::url($path);
            $messageType = 'video';
        }

        if ($request->input('link')) {
            $content = $request->input('link');
            $messageType = 'link';
        }

        $tweet->update([
            'content' => $content,
            'message_type' => $messageType,
        ]);

        return redirect()->route('tweets.index')->with('success', 'Message updated successfully');
    }


    public function destroy(Tweet $tweet)
    {
        // 画像が存在する場合はストレージから削除
        if ($tweet->message_type == 'image') {
            $imagePath = str_replace('/storage/', '', $tweet->content);
            Storage::disk('public')->delete($imagePath);
        }

        $tweet->delete();

        return redirect()->route('tweets.index')->with('success', 'Message deleted successfully');
    }
}
