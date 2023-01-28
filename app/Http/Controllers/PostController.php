<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PostController extends Controller
{

    public static $posts = [
        ['id' => 0, 'title' => 'Laravel', 'description' => "Laravel", 'post_creator' => 'Ahmed', 'created_at' => '2023-01-28 03:53:00'],
        ['id' => 1, 'title' => 'PHP', 'description' => "PHP", 'post_creator' => 'Ashraf', 'created_at' => '2023-01-28 10:37:00'],
        ['id' => 2, 'title' => 'Javascript', 'description' => "JS", 'post_creator' => 'Ibrahim', 'created_at' => '2023-01-28 03:53:00'],
    ];

    public function index()
    {
        // dd($posts); for debugging
        return view('posts.index', [
            'posts' => self::$posts,
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        return view('posts.index', [
            'posts' => self::$posts,
        ]);
    }

    public function show($postId)
    {
        return view('posts.show', ["post" => self::$posts[$postId]]);
    }

    public function edit($postId)
    {
        return view("posts.edit", ["post" => self::$posts[$postId]]);
    }

    public function update(Request $req)
    {
        $request = $req->all();
        self::$posts[$request['id']]['title'] = $request['title'];
        self::$posts[$request['id']]['description'] = $request['description'];
        self::$posts[$request['id']]['post_creator'] = $request['creator'];
        return view('posts.index', [
            'posts' => self::$posts,
        ]);
    }

    public function delete($id)
    {
        // return 'Deleted';
        unset(self::$posts[$id]);
        return view('posts.index', [
            'posts' => self::$posts,
        ]);
    }
}
