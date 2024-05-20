<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Tweet::all();
        return view('tweet.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        Tweet::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'content' => $request->input('content'),
            'message_type' => 'text'
        ]);

        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        return view('tweet.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        return view('tweet.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $tweet->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('tweets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        $tweet->delete();

        return redirect()->route('tweets.index');
    }
}
