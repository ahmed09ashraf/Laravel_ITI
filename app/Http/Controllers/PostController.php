<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;


class PostController extends Controller
{

    // public static $posts = [
    //     ['id' => 0, 'title' => 'Laravel', 'description' => "Laravel", 'post_creator' => 'Ahmed', 'created_at' => '2023-01-28 03:53:00'],
    //     ['id' => 1, 'title' => 'PHP', 'description' => "PHP", 'post_creator' => 'Ashraf', 'created_at' => '2023-01-28 10:37:00'],
    //     ['id' => 2, 'title' => 'Javascript', 'description' => "JS", 'post_creator' => 'Ibrahim', 'created_at' => '2023-01-28 03:53:00'],
    // ];

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        $users =  User::all();
        return view('posts.create', [
            'users' => $users,
        ]);
    }

    public function store()
    {
        $req = request()->all();
        Post::create([
            'title' =>  $req['title'],
            'description' =>  $req['description'],
            'user_id' => $req['post_creator'],
        ]);
        return redirect()->route('posts.index')->with('success', "post created");
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
    
    public function update() //update(Request $req)
    {
        $req = request()->all();
        post::where('id', $req['id'])->update([
            'title' => $req['title'],
            'description' => $req['description'] ,
            'user_id' => $req['creator']
        ]);
        return to_route('posts.index');
    }

    public function delete($postId)
    {
        post::where('id', $postId)->delete();
        return to_route('posts.index');
    }
}
