<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    public function index()
    {
        $messages = Tweet::all();
        return view('tweet.index', compact('messages'));
    }

    public function create()
    {
        return view('tweet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $tweet = new Tweet();
        $tweet->user_id = auth()->id();
        $tweet->user_name = auth()->user()->name;
        $tweet->updated_at = now();
        $tweet->created_at = now();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $tweet->content = '/storage/' . $imagePath;
            $tweet->message_type = 'image';
        } else {
            $tweet->content = $request->input('content');
            $tweet->message_type = 'text';
        }

        $tweet->save();

        return redirect()->route('tweets.index')->with('success', 'Tweet created successfully.');
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
