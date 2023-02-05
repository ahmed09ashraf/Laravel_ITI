<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(3);
        
        return view('posts.index', compact('posts',));
    }



    public function archive()
    {
        $posts = Post::onlyTrashed()->get();

        return view('posts.restore', compact('posts'));
    }



    public function create()
    {
        $users =  User::all();

        return view('posts.create', [
            'users' => $users,
        ]);
    }



    public function store(StorePostRequest $request)
    {
        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('public/images');
            Post::create([
                'title' =>  $request['title'],
                'description' =>  $request['description'],
                'user_id' => $request['post_creator'],
                'image' => str_replace('public', 'storage', $imagePath)
            ]);
        } else {
            Post::create([
                'title' =>  $request['title'],
                'description' =>  $request['description'],
                'user_id' => $request['post_creator'],

            ]);
        }

        return to_route('posts.index');
    }



    public function show($postId)
    {
        $post = Post::find($postId);
        $users =  User::all();

        return view("posts.show", [
            'post' => $post,
            'users' => $users,
        ]);
    }



    public function edit($postId)
    {
        $users = User::all();
        $post = Post::find($postId);

        return view('posts.edit', [
            'post' => $post,
            'users' => $users,
        ]);
    }



    public function update(Request $request, $postId)
    {
        // dd($request->all()) ;
        $users = User::all();
        $post = Post::find($postId);
        $request->validate([
            'title' => 'required|min:3|unique:posts,title,' . $post->title . ',title',
            'description' => 'required|min:10',
            'image' => 'mimes:jpeg,png,jpg,gif'
        ]);

        if ($request->file('image')) {
            Storage::delete(str_replace('storage', 'public', $post->image));
            $imagePath = $request->file('image')->store('public/images');
        } else {
            $imagePath = $post->image;
        }

        $post->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => str_replace('public', 'storage', $imagePath),
        ]);

        return view('posts.show', [
            'post' => $post,
            'users' => $users,
        ]);
    }



    public function delete($postId)
    {
        post::where('id', $postId)->delete();

        return to_route('posts.index');
    }



    public function restore($id)
    {
        Post::withTrashed()->find($id)->restore();

        return back();
    }
}
