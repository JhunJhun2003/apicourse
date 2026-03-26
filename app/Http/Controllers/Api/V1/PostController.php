<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author')->get();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id; // Assuming you have authentication set up
        $post = Post::create($data);

        return response()->json(['message' => 'Post created successfully', 'post' => new PostResource($post)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        abort_if(FacadesAuth::id() != $post->author_id, 403, 'Unauthorized');
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        abort_if(FacadesAuth::id() != $post->author_id, 403, 'Unauthorized');
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'body' => 'sometimes|required|string',
        ]);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $post->update($data);

        return response()->json(['message' => 'Post updated successfully', 'post' => new PostResource($post)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if(FacadesAuth::id() != $post->author_id, 403, 'Unauthorized');
        $post->delete();

        return response()->noContent();
    }
}
