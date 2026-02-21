<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::with('author')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = 1; // Assuming you have authentication set up
        $post = Post::create($data);
        return response()->json(['message' => 'Post created successfully', 'post' => new PostResource($post)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $post = Post::find($id);
        // if (!$post) {
        //     return response()->json(['message' => 'Post not found'], 404);
        // }
        // return $post;
        return new PostResource(Post::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'body' => 'sometimes|required|string'
        ]);
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $post->update($data);
        return response()->json(['message' => 'Post updated successfully', 'post' => new PostResource($post)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
