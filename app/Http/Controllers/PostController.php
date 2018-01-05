<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

use App\Http\Requests\StorePost as StorePostRequest;
use Auth;
use Gate;

class PostController extends Controller
{
    public function index()
	{
	    $posts = Post::published()->paginate();
	    return view('posts.index', compact('posts'));
	}

	public function create()
	{
	    return view('posts.create');
	}

	public function store(StorePostRequest $request)
	{
	    $data = $request->only('title', 'body');
	    $data['slug'] = str_slug($data['title']);
	    $data['user_id'] = Auth::user()->id;
	    $post = Post::create($data);
	    return redirect()->route('edit_post', ['id' => $post->id]);
	}

	public function drafts()
	{
	    $postsQuery = Post::unpublished();
	    if(Gate::denies('see-all-drafts')) {
	        $postsQuery = $postsQuery->where('user_id', Auth::user()->id);
	    }
	    $posts = $postsQuery->paginate();
	    return view('posts.drafts', compact('posts'));
	}
}
