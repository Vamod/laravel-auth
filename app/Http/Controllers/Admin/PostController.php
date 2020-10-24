<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Post;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // tutti i post corrispondenti allo id di chi è loggato
        $posts = Post::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(5);

        // $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data); mi restituisce l'array del post che voglio storare
        $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:5|max:500',
            'img' => 'image'
        ]);

        // prendo l'id
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title'],'-');
        // nuova istanza
        $newPost = new Post();

        if (!empty($data['img'])) {
            $data['img'] = Storage::disk('public')->put('images', $data['img']);
        }
        // la riempio con i dati
        $newPost->fill($data);

        // equivale a fare INSERT
        $saved = $newPost->save();

        $newPost->tags()->attach($data['tags']);
        if($saved){
            return redirect()->route('posts.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // dd($post); mi restituisce oggetto al cui interno c'è l'array del post selezionato
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // array di dati
        $data = $request->all();

        // dd($data); mi restituisce l'array del post che voglio storare
        $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:5|max:500',
            'img' => 'image'
        ]);

        $data['slug'] = Str::slug($data['title'],'-');
        $data['updated_at'] = Carbon::now('Europe/Rome');

        if (empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        } else {
            $post->tags()->detach();
        }


        if (!empty($data['img'])) {
            // cancella img precedente  
            if(!empty($post->img)){
                Storage::disk('public')->delete($post->img);
            }
            $data['img'] = Storage::disk('public')->put('images', $data['img']);
        }

        $updated = $post->update($data);

        if($updated){
            return redirect()->route('posts.index')->with('statusModifica', 'Hai modificato correttamente il post del id '.$post->id);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('statusCancella', 'Hai cancellato correttamente il post del id '.$post->id);
    }
}
