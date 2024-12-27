<?php

namespace App\Http\Controllers;

use App\Models\ReplyComment;
use Illuminate\Http\Request;

class ReplyCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_comments_id' => 'required|exists:post_comments,id',
            'content' => 'required|string',
        ]);

        $reply = ReplyComment::createReply($request->all());

        $response = [
            'content' => $reply->content,
            'created_at' => $reply->created_at,
            'full_name' => $reply->user->first_name . ' ' . $reply->user->last_name,
            'id' => $reply->id,
            'post_comments_id' => $reply->post_comments_id,
            'profile_photo' => $reply->user->profile_photo_path,
            'up_votes' => 0,
            'down_votes' => 0,
        ];

        return response()->json($response, 201);
    }

    public function upvote(Request $request, $post, $comment)
    {
        $replyComment = ReplyComment::findOrFail($comment);
        $user = auth()->user();

        if ($user->hasDownvotedReplyComment($replyComment)) {
            $user->removeDownvoteReplyComment($replyComment);
            $replyComment->down_votes--;
        }

        if ($user->hasUpvotedReplyComment($replyComment)) {
            $user->removeUpvoteReplyComment($replyComment);
            $replyComment->up_votes--;
        } else {
            $user->addUpvoteReplyComment($replyComment);
            $replyComment->up_votes++;
        }

        $replyComment->save();

        return response()->json([
            'up_votes' => $replyComment->up_votes,
            'down_votes' => $replyComment->down_votes,
            'user_vote' => $user->getReplyCommentVote($replyComment),
            'success' => true
        ]);
    }

    public function downvote(Request $request, $post, $comment)
    {
        $replyComment = ReplyComment::findOrFail($comment);
        $user = auth()->user();

        if ($user->hasUpvotedReplyComment($replyComment)) {
            $user->removeUpvoteReplyComment($replyComment);
            $replyComment->up_votes--;
        }

        if ($user->hasDownvotedReplyComment($replyComment)) {
            $user->removeDownvoteReplyComment($replyComment);
            $replyComment->down_votes--;
        } else {
            $user->addDownvoteReplyComment($replyComment);
            $replyComment->down_votes++;
        }

        $replyComment->save();

        return response()->json([
            'up_votes' => $replyComment->up_votes,
            'down_votes' => $replyComment->down_votes,
            'user_vote' => $user->getReplyCommentVote($replyComment),
            'success' => true
        ]);
    }

}
