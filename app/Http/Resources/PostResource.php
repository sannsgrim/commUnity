<?php

namespace App\Http\Resources;

use App\Models\CommentVote;
use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\PostVote;
use App\Models\ReplyCommentVote;
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
        $userId = $request->user()->id;

        $userVote = PostVote::where('post_id', $this->id)
            ->where('user_id', $userId)
            ->value('vote');

        $comments = PostComment::where('post_id', $this->id)
            ->get()
            ->map(function ($comment) use ($userId) {
                $user = $comment->user;
                $userVote = CommentVote::where('comment_id', $comment->id)
                    ->where('user_id', $userId)
                    ->value('vote');

                $replyComments = $comment->replyComments()->get()->map(function ($reply) use ($userId) {
                    $replyUser = $reply->user;
                    $replyUserVote = ReplyCommentVote::where('reply_comment_id', $reply->id)
                        ->where('user_id', $userId)
                        ->value('vote');
                    return [
                        'id' => $reply->id,
                        'profile_photo' => $replyUser->profile_photo_path,
                        'full_name' => $replyUser->first_name . ' ' . $replyUser->last_name,
                        'content' => $reply->content,
                        'up_votes' => $reply->up_votes,
                        'down_votes' => $reply->down_votes,
                        'created_at' => $reply->created_at,
                        'user_vote' => $replyUserVote,
                    ];
                });
                return [
                    'id' => $comment->id,
                    'profile_photo' => $user->profile_photo_path,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'post_id' => $comment->post_id,
                    'content' => $comment->content,
                    'up_votes' => $comment->up_votes,
                    'down_votes' => $comment->down_votes,
                    'created_at' => $comment->created_at,
                    'reply_count' => $replyComments->count(),
                    'reply_comments' => $replyComments,
                    'user_vote' => $userVote,
                ];
            });

        $commentsCount = $comments->count() + $comments->sum('reply_count');

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
            'comments_count' => $commentsCount,
        ];
    }
}
