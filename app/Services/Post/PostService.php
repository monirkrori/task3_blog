<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Filters\PostFilter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PostService
{

    /**
     * Get filtered list of posts
     *
     * @param array $filters
     */
    public function getposts(array $filters = [])
    {
        $query = Post::query();
        $query = (new PostFilter($filters))->apply($query);
        return $query
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get single post by ID
     *
     * @param int $id
     * @return Post
     */

    public function getPost(int $id): Post
    {
        return Cache::remember("post.{$id}", now()->addHour(), function () use ($id) {
            return Post::findOrFail($id);
        });
    }

    /**
     * Create new post
     *
     * @param array $data
     * @return Post
     * @throws \RuntimeException
     */
    public function createPost(array $data): Post
    {

        try {
            return DB::transaction(function () use ($data) {
                $data['user_id'] = auth()->id();
                return Post::create($data);
            });
        }

        catch (\Exception $exception){
            Log::error('Post creation failed: ' . $exception->getMessage());
            throw new \RuntimeException('Could not create post');

        }
    }

    /**
     * Update existing post
     *
     * @param Post $post
     * @param array $data
     * @return Post
     * @throws \RuntimeException
     */

    public function updatePost(Post $post, array $data): Post
    {
        $this->authorizePost($post);

        try {
            $post->update($data);
            Cache::forget("post.{post->id}");
            return $post;
        } catch (\Exception $e) {
            Log::error("Post update failed for ID {$post->id}: " . $e->getMessage());
            throw new \RuntimeException('Could not update post');
        }
    }

    /**
     * Delete post by ID
     *
     * @param int $id
     * @return bool
     * @throws \RuntimeException
     */

    public function deletePost(Post $post): bool
    {
        $this->authorizePost($post);

        try {
                $post->delete();
                Cache::forget("post.{$post->id}");
                return true;
        } catch (\Exception $exception) {
            Log::error("Post deletion failed for ID {$post->id}: " . $exception->getMessage());
            throw new \RuntimeException('Could not delete post');
        }
    }



    /**
     * Check if the authenticated user owns the post
     *
     * @param Post $post
     * @return void
     * @throws \RuntimeException
     */
    protected function authorizePost(Post $post): void
    {
        if ($post->user_id !== auth()->id()) {
            throw new \RuntimeException('Unauthorized');
        }
    }



}
