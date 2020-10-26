<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::all();

        return view('guests.index', compact('posts'));
    }

    public function show($slug){
        // si ferma al primo slug che matcha
        $post = Post::where('slug', $slug)->first();

        return view('guests.show', compact('post'));
    }
}
