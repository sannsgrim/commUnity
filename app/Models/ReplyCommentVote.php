<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReplyCommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reply_comment_id',
        'vote',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replyComment(): BelongsTo
    {
        return $this->belongsTo(ReplyComment::class);
    }
}
