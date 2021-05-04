<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create(Type $var = null)
    {
        return view('review');
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:500',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {

            $request->file('image')->store('/public/images');
            $data = [
                'user_id' => \Auth::id(),
                'title' => $post['title'],
                'body' => $post['body'],
                'image' => $request->file('image')->hashName(),
            ];
        } else {
            $data = [
                'user_id' => \Auth::id(),
                'title' => $post['title'],
                'body' => $post['body'],
            ];
        }

        Review::insert($data);

        return redirect()->route('index')->with('flash_message', '投稿しました');
    }
}
