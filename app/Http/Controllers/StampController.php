<?php

namespace App\Http\Controllers;

use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // バリデーション
        $request->validate([
            'image' => 'required|image|mimes:png|max:2048', // 画像は必須、PNG形式、最大2MB
        ]);

        // 画像ファイルを保存
        $path = $request->file('image')->store('public/stamps');

        // ファイルパスを取得
        $filePath = Storage::url($path);

        // データベースに保存
        $stamp = new Stamp();
        $stamp->user_id = auth()->id(); // 認証されたユーザーのIDを取得
        $stamp->image = $filePath;
        $stamp->save();

        return response()->json(['success' => true, 'message' => 'スタンプが作成されました。']);
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
