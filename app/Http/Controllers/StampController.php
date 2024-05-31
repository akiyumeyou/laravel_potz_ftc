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
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 画像を保存するディレクトリを定義
        $uploadDir = 'public/stamps';
        if (!Storage::exists($uploadDir)) {
            Storage::makeDirectory($uploadDir);
        }

        // 画像を保存
        $imageName = time() . '.png';
        $imagePath = $uploadDir . '/' . $imageName;

        // $image = Image::make($request->file('image'))->resize(320, 440)->encode('png');
        Storage::put($imagePath, (string) $imageName);

        // データベースに保存
        $stamp = new Stamp();
        $stamp->user_id = Auth::id();
        $stamp->image = 'storage/stamps/' . $imageName; // 公開ディレクトリへのパス
        $stamp->save();

        return response()->json(['success' => true]);
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
