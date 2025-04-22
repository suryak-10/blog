<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        return Post::with(['user', 'categories', 'tags'])->latest()->get();
    }

    public function show($id) {
        return Post::with(['user', 'categories', 'tags'])->findOrFail($id);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'slug' => 'required|unique:posts',
            'content' => 'required',
            'status' => 'in:draft,published,archived',
            'user_id' => 'required|exists:users,id',
        ]);
        return Post::create($request->all());
    }

    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return $post;
    }

    public function destroy($id) {
        return Post::destroy($id);
    }
}