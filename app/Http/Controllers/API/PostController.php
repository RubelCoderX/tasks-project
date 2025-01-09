<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Http\Controllers\API\BaseController as BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();
        return $this->sendRespons('Posts retrieved successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'description' => 'required',
                'due_date' => 'required|date',
                'status' => 'required|in:pending,in-progress,completed',
            ]
        );

        if ($validatePost->fails()) {
            return $this->sendError('Validation Error', $validatePost->errors()->all());
        }

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return $this->sendRespons('Post created successfully', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Post not found');
        }

        return $this->sendRespons('Single Post retrieved successfully', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'description' => 'required',
                'due_date' => 'required|date',
                'status' => 'required|in:pending,in-progress,completed',
            ]
        );

        if ($validatePost->fails()) {
            return $this->sendError('Validation Error', $validatePost->errors()->all());
        }

        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Post not found');
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return $this->sendRespons('Post updated successfully', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Post not found');
        }

        $post->delete();

        return $this->sendRespons('Post deleted successfully', []);
    }
}

