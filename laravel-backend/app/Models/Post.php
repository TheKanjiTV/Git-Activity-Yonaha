<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    protected $fillable = [
        'message',
        'post',
        'description',
        'status',
        'user_id',
    ];

    protected $appends = [
        'message',
    ];

    protected function message(): Attribute
    {
        return Attribute::get(
            fn (?string $value): ?string => $value ?? $this->attributes['description'] ?? $this->attributes['post'] ?? null
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
