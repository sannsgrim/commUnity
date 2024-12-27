<?php

namespace App\Http\Resources;

use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\PostVote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Assuming you have the authenticated user available
        $userId = $request->user()->id;

        // Fetch the user vote for the post
        $userVote = PostVote::where('post_id', $this->id)
            ->where('user_id', $userId)
            ->value('vote');

        // Fetch comments and their replies
        $comments = PostComment::where('post_id', $this->id)
            ->get()
            ->map(function ($comment) {
                $user = $comment->user;
                return [
                    'id' => $comment->id,
                    'profile_photo' => $user->profile_photo_path,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'post_id' => $comment->post_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                ];
            });


        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_vote' => $userVote,
            'caption' => $this->caption,
            'up_votes' => $this->up_votes,
            'down_votes' => $this->down_votes,
            'created_at' => $this->created_at,
            'images' => PostImage::where('post_id', $this->id)->get()->map(function ($image) {
                return [
                    'post_id' => $image->post_id,
                    'image_path' => $image->image_path,
                ];
            }),
            'user_details' => User::where('id', $this->user_id)->get()->map(function ($user) {
                return [
                    'profile_photo_path' => $user->profile_photo_path,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ];
            }),
            'comments' => $comments,
            'comments_count' => $comments->count(),
        ];
    }
}
