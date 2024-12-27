<?php

namespace App\Http\Controllers;

use App\Models\ReplyComment;
use Illuminate\Http\Request;

class ReplyCommentController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'post_comments_id' => 'required|exists:post_comments,id',
            'content' => 'required|string',
        ]);

        $reply = ReplyComment::createReply($request->all());

        return response()->json($reply, 201);
    }
}
