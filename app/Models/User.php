<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\SentOtpMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_photo_path',
        'cover_photo_path',
        'email_verification_code',
        'email_verification_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_verification_code_expires_at' => 'datetime',
        ];
    }

    public function updateProfileImage($image): void
    {
        // Check if the current profile photo is not the default image
        if ($this->profile_photo_path !== 'profile-picture/default.png') {
            // Delete the existing profile photo
            \Storage::disk('public')->delete($this->profile_photo_path);
        }

        // Store the new profile photo
        $path = $image->store('profile-picture', 'public');

        // Update the profile photo path
        $this->update(['profile_photo_path' => $path]);
    }

    public function updateCoverImage($image): void
    {
        // Check if the current profile photo is not the default image
        if ($this->cover_photo_path !== 'cover-photo/default.png') {
            // Delete the existing profile photo
            \Storage::disk('public')->delete($this->profile_photo_path);
        }

        // Store the new profile photo
        $path = $image->store('cover-photo', 'public');

        // Update the profile photo path
        $this->update(['cover_photo_path' => $path]);
    }

    public function generateEmailVerificationCode(): void
    {
        if ($this->email_verification_code_expires_at && $this->email_verification_code_expires_at->isFuture()) {
            return;
        }

        $this->email_verification_code = Str::random(6);
        $this->email_verification_code_expires_at = Carbon::now()->addMinutes(10);
        $this->save();

        // Send the verification code via email using SentOtpMail
        Mail::to($this->email)->send(new SentOtpMail($this->email_verification_code));
    }

    //Post Algo Vote
    public function votes()
    {
        return $this->hasMany(PostVote::class);
    }

    public function hasUpvoted(Post $post)
    {
        return $this->votes()->where('post_id', $post->id)->where('vote', 'up')->exists();
    }

    public function hasDownvoted(Post $post)
    {
        return $this->votes()->where('post_id', $post->id)->where('vote', 'down')->exists();
    }

    public function addUpvote(Post $post)
    {
        $this->votes()->updateOrCreate(['post_id' => $post->id], ['vote' => 'up']);
    }

    public function removeUpvote(Post $post)
    {
        $this->votes()->where('post_id', $post->id)->where('vote', 'up')->delete();
    }

    public function addDownvote(Post $post)
    {
        $this->votes()->updateOrCreate(['post_id' => $post->id], ['vote' => 'down']);
    }

    public function removeDownvote(Post $post)
    {
        $this->votes()->where('post_id', $post->id)->where('vote', 'down')->delete();
    }

    public function getVote(Post $post)
    {
        return $this->votes()->where('post_id', $post->id)->value('vote');
    }

    //Comment ALgo Vote
    public function commentVotes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public function hasUpvotedComment(PostComment $comment)
    {
        return $this->commentVotes()->where('comment_id', $comment->id)->where('vote', 'up')->exists();
    }

    public function hasDownvotedComment(PostComment $comment)
    {
        return $this->commentVotes()->where('comment_id', $comment->id)->where('vote', 'down')->exists();
    }

    public function addUpvoteComment(PostComment $comment)
    {
        $this->commentVotes()->updateOrCreate(['comment_id' => $comment->id], ['vote' => 'up']);
    }

    public function removeUpvoteComment(PostComment $comment)
    {
        $this->commentVotes()->where('comment_id', $comment->id)->where('vote', 'up')->delete();
    }

    public function addDownvoteComment(PostComment $comment)
    {
        $this->commentVotes()->updateOrCreate(['comment_id' => $comment->id], ['vote' => 'down']);
    }

    public function removeDownvoteComment(PostComment $comment)
    {
        $this->commentVotes()->where('comment_id', $comment->id)->where('vote', 'down')->delete();
    }

    public function getCommentVote(PostComment $comment)
    {
        return $this->commentVotes()->where('comment_id', $comment->id)->value('vote');
    }

    //Reply ALgo Vote
    public function replyCommentVotes()
    {
        return $this->hasMany(ReplyCommentVote::class);
    }

    public function hasUpvotedReplyComment(ReplyComment $replyComment)
    {
        return $this->replyCommentVotes()->where('reply_comment_id', $replyComment->id)->where('vote', 'up')->exists();
    }

    public function hasDownvotedReplyComment(ReplyComment $replyComment)
    {
        return $this->replyCommentVotes()->where('reply_comment_id', $replyComment->id)->where('vote', 'down')->exists();
    }

    public function addUpvoteReplyComment(ReplyComment $replyComment)
    {
        $this->replyCommentVotes()->updateOrCreate(['reply_comment_id' => $replyComment->id], ['vote' => 'up']);
    }

    public function removeUpvoteReplyComment(ReplyComment $replyComment)
    {
        $this->replyCommentVotes()->where('reply_comment_id', $replyComment->id)->where('vote', 'up')->delete();
    }

    public function addDownvoteReplyComment(ReplyComment $replyComment)
    {
        $this->replyCommentVotes()->updateOrCreate(['reply_comment_id' => $replyComment->id], ['vote' => 'down']);
    }

    public function removeDownvoteReplyComment(ReplyComment $replyComment)
    {
        $this->replyCommentVotes()->where('reply_comment_id', $replyComment->id)->where('vote', 'down')->delete();
    }

    public function getReplyCommentVote(ReplyComment $replyComment)
    {
        return $this->replyCommentVotes()->where('reply_comment_id', $replyComment->id)->value('vote');
    }

}
