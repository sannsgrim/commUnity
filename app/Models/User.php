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

}
