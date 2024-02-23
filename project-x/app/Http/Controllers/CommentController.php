<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    function store(Request $formData)
    {
        $comment = new Comment();
        $comment->body = $formData->comment;
        $user = $formData->user();
        $postId = request('id');
        $post = Post::find($postId);
        $comment->post()->associate($post);
        $comment->user()->associate($user);
        $comment->likes = 0;
        $comment->save();

        return back()->withInput();
    }

    function like(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $commentId = request('id');
        $comment = Comment::find($commentId);

        $isLiked = Like::where('user_id', $userId)
            ->where('comment_id', $commentId)
            ->first();

        if ($isLiked) {
            $isLiked->delete();
            $comment->likes--;
            $comment->save();

            return back()->withInput();
        }

        $comment->likes++;
        $comment->save();

        $like = new Like();
        $like->comment()->associate($comment);
        $like->user()->associate($user);
        $like->save();

        return back()->withInput();
    }
}
