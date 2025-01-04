<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'caption',
        'up_votes',
        'down_votes'
    ];

    public static function createPost($userId, $caption, $imagePaths): array
    {
        $storedImagePaths = [];

        // Create a new Post instance and save it to the database
        $post = self::create([
            'user_id' => $userId,
            'caption' => $caption,
            'up_votes' => 0,
            'down_votes' => 0,
        ]);

        foreach ($imagePaths as $image) {
            $imageData = $image['src'];
            $imageName = $image['name'];

            // Decode the base64 data
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

            // Create a temporary file
            $tempFilePath = sys_get_temp_dir() . '/' . $imageName;
            file_put_contents($tempFilePath, $imageData);

            // Create an UploadedFile instance
            $tempFile = new UploadedFile($tempFilePath, $imageName, null, null, true);

            // Store the image in the 'public/posted-images' directory
            $storedImagePath = $tempFile->storeAs('posted-images', $imageName, 'public');
            $storedImagePaths[] = $storedImagePath;

            // Create a new PostImage instance and save it to the database
            PostImage::create([
                'post_id' => $post->id,
                'image_path' => $storedImagePath,
            ]);
        }

        return ['post' => $post, 'stored_image_paths' => $storedImagePaths];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PostVote::class);
    }
}
