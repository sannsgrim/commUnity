<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response('Hello, World!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        $caption = $request->input('postText');
        $imagePaths = $request->input('imagePaths');

        Post::createPost($userId, $caption, $imagePaths);

        // Redirect to the test.dashboard route
        return redirect()->route('test.dashboard');
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function upvote(Post $post)
    {
        $user = auth()->user();

        if ($user->hasDownvoted($post)) {
            $post->down_votes--;
            $user->removeDownvote($post);
        }

        if ($user->hasUpvoted($post)) {
            $post->up_votes--;
            $user->removeUpvote($post);
        } else {
            $post->up_votes++;
            $user->addUpvote($post);
        }

        $post->save();

        return response()->json([
            'up_votes' => $post->up_votes,
            'down_votes' => $post->down_votes,
            'user_vote' => $user->getVote($post)
        ]);
    }

    public function downvote(Post $post)
    {
        $user = auth()->user();

        if ($user->hasUpvoted($post)) {
            $post->up_votes--;
            $user->removeUpvote($post);
        }

        if ($user->hasDownvoted($post)) {
            $post->down_votes--;
            $user->removeDownvote($post);
        } else {
            $post->down_votes++;
            $user->addDownvote($post);
        }

        $post->save();

        return response()->json([
            'up_votes' => $post->up_votes,
            'down_votes' => $post->down_votes,
            'user_vote' => $user->getVote($post)
        ]);
    }

}
