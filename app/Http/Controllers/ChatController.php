<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Exception;

class ChatController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function handle(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|max:1000',
            ]);

            $text = $request->input('text');
            
            // 100文字以上の場合は要約
            $summarizedText = $this->openAIService->summarizeText($text);
            
            // ChatGPTの応答を生成
            $responseText = $this->openAIService->generateResponse($summarizedText);

            // データベースに保存
            $response = new Response();
            $response->user_id = 1; // ユーザーIDを1に固定
            $response->original_text = $text;
            $response->summarized_text = $summarizedText;
            $response->ai_response = $responseText;
            $response->save();
            
            return response()->json([
                'response' => $responseText,
                'summarizedText' => $summarizedText,
                'timestamp' => now()->format('m/d H:i')
            ]);

        } catch (Exception $e) {
            // エラーメッセージをログに記録
            Log::error($e->getMessage());

            // クライアントにエラーレスポンスを返す
            return response()->json(['error' => 'Failed to load conversation history'], 500);
        }
    }
    public function getConversationHistory()
    {
        // ログインしているユーザーの会話履歴を取得
        $responses = Response::where('user_id', Auth::id())->get();

        // 必要なデータだけを抽出して返す
        $history = $responses->map(function($response) {
            return [
                'sender' => Auth::user()->name,
                'text' => $response->original_text,
                'response' => $response->ai_response,
                'timestamp' => $response->created_at->format('H:i:s'),
            ];
        });

        return response()->json($history);
    }
}
