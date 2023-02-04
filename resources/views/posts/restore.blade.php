@extends('layouts.app')

@section('title')
Restore Deleted data
@endsection

@section('content')
{{-- <div class="text-center">
    <a href="{{ route('posts.create') }}" class="mt-4 btn btn-success">Create Post</a>
</div> --}}
<table class="table mt-4">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Posted By</th>
            <th scope="col">Deleted At</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>

        <tbody class="table-group-divider">
                @foreach ($posts as $post )
                <tr>
                    <td>{{ $post->id}}</td>
                    <td>{{ $post->title}}</td>
                    @if($post->user)
                    <td>{{$post->user->name}}</td>
                    @else
                    <td>Not Found</td>
                    @endif
                    <td>{{$post->deleted_at}}</td>
                <td class="d-flex justify-content-center  ">


                    <form action="{{ route('posts.restore', ['post' => $post->id]) }}" method="POST">
                        @csrf
                        @method('patch')
                        <button type="submit" onclick="return confirm('Are you Sure you want to restore post : {{$post->title}}?')" class="m-2 h-75 btn btn-danger">Restore</>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>

@endsection
