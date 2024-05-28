<?php

namespace App\Http\Controllers;

use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('stamps'), $imageName);

        $stamp = new Stamp();
        $stamp->user_id = Auth::id();
        $stamp->image = 'stamps/' . $imageName;
        $stamp->save();

        return redirect()->route('stamp.create')->with('success', 'スタンプが作成されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stamp $stamp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stamp $stamp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stamp $stamp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stamp $stamp)
    {
        //
    }
}
