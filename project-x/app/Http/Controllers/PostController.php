<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Repost;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Number;

class PostController extends Controller
{

    function like(Request $request)
    {
        $user = request()->user();
        $userId = $user->id;
        $postId = request('id');
        $post = Post::find($postId);

        $isLiked = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($isLiked) {
            $isLiked->delete();
            $post->likes--;
            $post->save();

            return back()->withInput();
        }

        $post->likes++;
        $post->save();

        $like = new Like();
        $like->post()->associate($post);
        $like->user()->associate($user);
        $like->save();

        return back()->withInput();

    }
    function showAll(Request $request)
    {
        $allPosts = Post::all();
        $sorted = $allPosts->sortBy('created_at', SORT_REGULAR, true);

        return view('dashboard', ["data" => $sorted]);
    }

    public function edit(Post $post)
    {

        return view('post.edit', compact('post'));
    }

    public function filter(Request $request)
    {
        $filter = $request->input('filter');

        if ($filter === 'recent') {
            $posts = Post::orderBy('created_at', 'desc')->get();
        } elseif ($filter === 'liked') {
            $posts = Post::orderBy('likes', 'desc')->get();
        } else {
            $posts = Post::all();
        }

        $allPosts = Post::all();
        $sorted = $allPosts->sortBy('created_at', SORT_REGULAR, true);

        return view('dashboard', ['data' => $posts, $sorted]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:64', 'min:4'],
            'body' => ['required', 'string', 'max:1100', 'min:4'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg, jpg, png, gif, webp'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect('/dashboard')->with('updated', 'Post updated successfully!');

    }

    function create()
    {

        return view('post.create');
    }

    function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:64', 'min:4'],
            'body' => ['required', 'string', 'max:1100', 'min:4'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg, jpg, png, gif, webp'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = null;
        }

        $user = $request->user();
        $excerpt = substr($request->body, 0, 100) . '...';

        $post = new Post();
        $post->title = $request->title;
        $post->excerpt = $excerpt;
        $post->body = $request->body;
        $post->image = $imagePath;
        $post->reposts = 0;
        $post->likes = 0;
        $post->user_id = $user->id;
        $post->save();

        return redirect('/dashboard')->with('success', 'Post is created!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id === auth()->id()) {
            $post->delete();
        }

        return redirect()->back()->with('delete', 'Post is deleted.');
    }

    // public function repost(Post $post)
    // {
    //     $user = auth()->user();
    //     if ($user->hasReposted($post)) {
    //         return back()->with('error', 'You have reported this post');
    //     }


    //     $repost = new Repost();
    //     $repost->user_id = $user->id;
    //     $repost->post_id = $post->id;
    //     $repost->save();

    //     return back()->with('reposted', 'Post is reposted!');
    // }
}
