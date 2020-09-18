<?php

namespace App\Http\Controllers;

use App\{Post, Category, Tag};
use App\Http\Requests\PostRequest;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function index() {

        return view('posts.index', [
            'posts' => Post::latest()->paginate(6),
        ]);
    }

    public function show(Post $post) { 

        $posts = Post::latest()->limit(6)->get();
        return view('posts.show', compact('post','posts'));
    }

    public function create() { 
        return view('posts.create', [
            'post'        => new Post(),
            'categories'  => Category::get(),
            'tags'        => Tag::get(),
        ]);
    }

    public function store(PostRequest $request) { 


        $request->validate([
            'thumbnail' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);
        
        $attr = $request->all();

        $slug = Str::of(request('title'))->slug('-');
        $attr['slug'] = $slug;

        $thumbnail = request()->file('thumbnail') ? request()->file('thumbnail')->store("images/posts") : null;

        $attr['category_id'] = request('category');
        $attr['thumbnail'] = $thumbnail;
        
        // Create new post
        $post = auth()->user()->posts()->create($attr);

        $post->tags()->attach(request('tags'));

        session()->flash('success', 'berhasil!');

        return redirect('post');

    }

    public function edit(Post $post) { 
        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::get(),
            'tags' => Tag::get(),
        ]);
    }

    public function update(PostRequest $request, Post $post) { 

        $request->validate([
            'thumbnail' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        $this->authorize('update', $post);

        if(request()->file('thumbnail')) {
            \Storage::delete($post->thumbnail);
            $thumbnail = request()->file('thumbnail')->store("images/posts"); 
        } else {
            $thumbnail = $post->thumbnail;
        }
                
        $attr = $request->all();
        $attr['category_id'] = request('category');
        $attr['thumbnail'] = $thumbnail;
        

        $post->update($attr);
        $post->tags()->sync(request('tags'));

        session()->flash('success', 'berhasil update!');

        return redirect('post');

    }

    public function destroy(Post $post) {

        $this->authorize('delete', $post);
        \Storage::delete($post->thumbnail);
        $post->tags()->detach();
        $post->delete();
        session()->flash('error', 'post ini bukan punya anda');
        return redirect('post');


        
    }


}
