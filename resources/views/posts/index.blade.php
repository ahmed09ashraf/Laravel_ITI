@extends('layouts.app')

@section('title')Index @endsection

@section('content')
<div class="text-center">
    <a href="{{ route('posts.create') }}" class="mt-4 btn btn-success">Create Post</a>
    <a href="{{ route('posts.archive') }}" class="mt-4 btn btn-danger"> Deleted Posts</a>
</div>
<table class="table mt-4">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Posted By</th>
            <th scope="col">Created At</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $posts as $post)
        <tr>
            <td>{{ $post->id}}</td>
            <td>{{ $post->title}}</td>
            @if($post->user)
            <td>{{$post->user->name}}</td>
            @else
            <td>Not Found</td>
            @endif
            <td>{{ \Carbon\Carbon::parse( $post->created_at )->toDateString(); }}</td>
            <td>
                <a href="{{ route('posts.show', ['post' => $post['id']]) }}" class="btn btn-info">View</a>
                <a href="{{ route('posts.edit', ['post' => $post['id']]) }}" class="btn btn-primary">Edit</a>
                {{-- <a href="{{ route('posts.delete', ['post' => $post['id']]) }}" class="btn btn-danger">Delete</a> --}}
                <form style="display: inline" method="POST" action="{{ route('posts.delete', ['post' => $post->id]) }}">
                    @method('DELETE')
                    @csrf
                    <button onclick="return confirm('Are you sure you want to delete this post?');" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center m-3">
    <span>
        {{$posts->links()}}
    </span>
</div>
    @endsection