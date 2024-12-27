<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'content', 'up_votes', 'down_votes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replyComments(): HasMany
    {
        return $this->hasMany(ReplyComment::class, 'post_comments_id');
    }

    public static function createComment(array $data): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'post_id' => $data['post_id'],
            'content' => $data['content'],
        ]);
    }

}
