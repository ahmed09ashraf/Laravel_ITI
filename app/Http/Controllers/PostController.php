<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    // public static $posts = [
    //     ['id' => 0, 'title' => 'Laravel', 'description' => "Laravel", 'post_creator' => 'Ahmed', 'created_at' => '2023-01-28 03:53:00'],
    //     ['id' => 1, 'title' => 'PHP', 'description' => "PHP", 'post_creator' => 'Ashraf', 'created_at' => '2023-01-28 10:37:00'],
    //     ['id' => 2, 'title' => 'Javascript', 'description' => "JS", 'post_creator' => 'Ibrahim', 'created_at' => '2023-01-28 03:53:00'],
    // ];

    public function index()
    {
        // $posts = Post::all();
        // return view('posts.index', [
        //     'posts' => $posts,
        // ]);
        $posts = Post::paginate(3);
        return view('posts.index', compact('posts',));

        
     

        // $posts = Post::onlyTrashed()->get();
        // return view('posts.restore', compact('posts',));
        // Post :: where('id',1)->withTrashed -> get() ;
        
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

    // public function store()
    // {
    //     $req = request()->all();
    //     Post::create([
    //         'title' =>  $req['title'],
    //         'description' =>  $req['description'],
    //         'user_id' => $req['post_creator'],
    //     ]);
    //     return redirect()->route('posts.index')->with('success', "post created");
    // }


    public function store(StorePostRequest $request)
    {    
        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('public/images');
            Post::create([
                'title' =>  $request['title'],
                'description' =>  $request['description'],
                'user_id' => $request['post_creator'],
                // 'image' => $imageRename,
                'image' => str_replace('public', 'storage', $imagePath)
            ]);
        } else {
            Post::create([
                'title' =>  $request['title'],
                'description' =>  $request['description'],
                'user_id' => $request['post_creator'],
                
            ]);
        }
     
        // return redirect()->route('posts.index')->with('success', "post created");
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
    
    // public function update() //update(Request $req)
    // {
    //     $req = request()->all();
    //     post::where('id', $req['id'])->update([
    //         'title' => $req['title'],
    //         'description' => $req['description'] ,
    //         'user_id' => $req['creator']
    //     ]);
    //     return to_route('posts.index');
    // }

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
        // dd($imagePath) ;
        $post->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => "public/images/210RE02X4fsYWgGh743CLZIztYE50IWTCUgEcMwN.jpg",
            // 'image' => str_replace('public', 'storage', $imagePath),
        ]);
        // return to_route('posts.index');
        return view('posts.show', [
            'post' => $post,
            'users' => $users,
        ]);
    }


    public function delete($postId)
    {
        post::where('id', $postId)->delete();
        // view('restore', compact('posts'));
        return to_route('posts.index');
    }

    public function restore($id)
    {
        Post::withTrashed()->find($id)->restore();
        return back() ;
    }

}
