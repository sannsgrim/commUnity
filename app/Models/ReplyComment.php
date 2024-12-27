<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReplyComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_comments_id', 'content', 'up_votes', 'down_votes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function postComment(): BelongsTo
    {
        return $this->belongsTo(PostComment::class);
    }

    public static function createReply(array $data): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'post_comments_id' => $data['post_comments_id'],
            'content' => $data['content'],
        ]);
    }

}
