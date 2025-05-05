<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreteBlogRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Str;

class PostController extends Controller
{
    public function index() {
        return Post::with(['user', 'categories', 'tags'])->latest()->get();
    }
    
    public function show($id) {
        return Post::with(['user', 'categories', 'tags'])->findOrFail($id);
    }

    public function store(CreteBlogRequest $request) {
        $user = auth()->user();
        // $request->validated();
        $payload = $request->except(['thumbnail', "blogTitle", "category_ids"]);
        $path = "blog-thumbnail/6eT4YAP6HnhwoxbkOzeDFQv1pfOWT2fuixNecLAe.png";
        $payload['slug'] = Str::slug($request->blogTitle);
        if($request->hasFile("thumbnail")){
            $path = $request->file('thumbnail')->store('/blog-thumbnail', 'public');
        }
        $blog = Post::create([
            ...$payload,
            'content' => $request->content,
              'user_id' => $user->id,
             "title" => $request->blogTitle,
             "thumbnail" => $path
            ]);
        $blog->categories()->attach($request->category_ids);
        return response()->json(compact("blog"));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => "required|string|unique:posts,title,{$id},id",
            // 'slug' => "required|unique:posts,slug,{$id},id",
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable',
            "category_ids" => "required|array",
        ]);
        $payload = $request->except('thumbnail', "category_ids");
        $post = Post::findOrFail($id);
        $path = $post->thumbnail;
        if($request->hasFile("thumbnail")){
            $path = $request->file('thumbnail')->store('/blog-thumbnail', 'public');
        }
        $payload['slug'] = Str::slug($payload['title']);
        $post->update([
            ...$payload,
             "thumbnail" => $path
        ]);
        $post->categories()->sync($request->category_ids);
        return $post;
    }

    public function destroy($id) {
        Post::destroy($id);
        return response()->json(['message' => "Post deleted"]);
    }

    public function myBlogs(){
        $user = auth()->user();
        return Post::with(['user', 'categories', 'tags'])->where("user_id", $user->id)->latest()->get();
    }

    public function categoryBlogs(Request $request, $categoryId){
        $blogs = Post::with(['user', 'categories', 'tags'])
        ->whereHas("categories", function($query)use($categoryId){
            $query->where("category_id", $categoryId);
        })
        ->latest()->get();
        return response()->json(compact("blogs"));
    }
    
}
