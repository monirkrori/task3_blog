<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'user_id',
        'slug',
        'body',
        'is_published',
        'publish_date',
        'meta_description',
        'tags',
        'keywords',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'publish_date' => 'datetime',
        'tags' => 'array',
        'keywords' => 'array',
    ];

    public function user():belongsTo{
        return $this->belongsTo(User::class);
    }
}
