<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $comment = PostComment::createComment($request->all());

        $response = [
            'content' => $comment->content,
            'created_at' => $comment->created_at,
            'full_name' => $comment->user->first_name . ' ' . $comment->user->last_name,
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'profile_photo' => $comment->user->profile_photo_path,
            'reply_comments' => $comment->replyComments,
            'reply_count' => $comment->replyComments->count(),
            'up_votes' => 0,
            'down_votes' => 0,
        ];

        return response()->json($response, 201);
    }

    public function upvote(Request $request, $post, $comment)
    {
        $comment = PostComment::findOrFail($comment);
        $user = auth()->user();

        if ($user->hasDownvotedComment($comment)) {
            $user->removeDownvoteComment($comment);
            $comment->down_votes--;
        }

        if ($user->hasUpvotedComment($comment)) {
            $user->removeUpvoteComment($comment);
            $comment->up_votes--;
        } else {
            $user->addUpvoteComment($comment);
            $comment->up_votes++;
        }

        $comment->save();

        return response()->json([
            'up_votes' => $comment->up_votes,
            'down_votes' => $comment->down_votes,
            'user_vote' => $user->getCommentVote($comment),
            'success' => true
        ]);
    }

    public function downvote(Request $request, $post, $comment)
    {
        $comment = PostComment::findOrFail($comment);
        $user = auth()->user();

        if ($user->hasUpvotedComment($comment)) {
            $user->removeUpvoteComment($comment);
            $comment->up_votes--;
        }

        if ($user->hasDownvotedComment($comment)) {
            $user->removeDownvoteComment($comment);
            $comment->down_votes--;
        } else {
            $user->addDownvoteComment($comment);
            $comment->down_votes++;
        }

        $comment->save();

        return response()->json([
            'up_votes' => $comment->up_votes,
            'down_votes' => $comment->down_votes,
            'user_vote' => $user->getCommentVote($comment),
            'success' => true
        ]);
    }

}
