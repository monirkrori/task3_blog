<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Services\Post\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {

        $this->postService = $postService;
    }

    /**
     * Display a listing of the posts.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'is_published', 'search', 'tags', 'sort_by', 'sort_direction', 'per_page'
        ]);

        $posts = $this->postService->getPosts($filters);

        return $this->responseWitSuccess($posts,'List of data return successful','200');

    }

    /**
     * Store a newly created post.
     */
    public function store(StorePostRequest $request)
    {
       $data = $request->validated();
       $post = $this->postService->createPost($data);
       return $this->responseWitSuccess($post,'Post created successful','201');    }

    /**
     * Display the specified post.
     */
    public function show($id)
    {
        $post = $this->postService->getPost($id);

        return $this->responseWitSuccess($post,'Post retrieved successful','200');
        }

    /**
     * Update the specified post.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        $updatedPost = $this->postService->updatePost($post, $data);

        return $this->responseWitSuccess($updatedPost,'Post updated successful','200');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);
        return $this->responseWitSuccess($post,'Post deleted successful','200');
    }
}
