<?php

namespace App\Http\Controllers;

use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


use Intervention\Image\ImageManagerStatic as Image;

class StampController extends Controller
{
    public function index()
    {
        // 必要に応じて実装
    }

    public function create()
    {
        return view('stamp.create');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Store method called');

            // バリデーション
            $request->validate([
                'image' => 'required|image|mimes:png|max:2048', // 画像は必須、PNG形式、最大2MB
            ]);

            Log::info('Validation passed');

            // ファイル情報のログ
            $file = $request->file('image');
            Log::info('File details: ' . $file->getMimeType() . ', ' . $file->getClientOriginalExtension());

            // 画像ファイルを保存
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs('public/stamps', $originalName);
            Log::info('Image stored at: ' . $path);

            // ファイルパスを取得
            $filePath = Storage::url($path);
            Log::info('File URL: ' . $filePath);

            // データベースに保存
            $stamp = new Stamp();
            $stamp->user_id = auth()->id(); // 認証されたユーザーのIDを取得
            $stamp->image = $filePath;
            $stamp->save();

            return response()->json(['success' => true, 'message' => 'スタンプが作成されました。']);
        } catch (\Exception $e) {
            Log::error('エラーが発生しました: ' . $e->getMessage());
            // エラーメッセージをJSON形式で返す
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function show(Stamp $stamp)
    {
        // 必要に応じて実装
    }

    public function edit(Stamp $stamp)
    {
        // 必要に応じて実装
    }

    public function update(Request $request, Stamp $stamp)
    {
        // 必要に応じて実装
    }

    public function destroy(Stamp $stamp)
    {
        // 必要に応じて実装
    }
}
