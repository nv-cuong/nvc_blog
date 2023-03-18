<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::User()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.create', compact(['categories', 'tags']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required',
            'categories' => 'required',
            'tags'  => 'required',
            'body' => 'required',
        ]);
        $image = $request->file('image');
        $slug = Str::slug($request->title);
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('post')) {
                Storage::disk('public')->makeDirectory('post');
            }
            $post_image = Image::make($image)->resize(1600, 1066)->stream();
            Storage::disk('public')->put('post/' . $imageName, $post_image);
        } else {
            $imageName = "default.png";
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if (isset($request->status)) {
            $post->status = true;
        } else {
            $post->status = false;
        }
        $post->approve = true;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        Toastr::success('Post Successfully created!', 'Success');
        return redirect()->route('author.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if($post->user_id != Auth::id()){
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->route('author.post.index');
        }
        return view('author.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if($post->user_id != Auth::id()){
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->route('author.post.index');
        }
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit', compact(['post', 'categories', 'tags']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if($post->user_id != Auth::id()){
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->route('author.post.index');
        }
        $this->validate($request, [
            'title' => 'required',
            'image' => 'image',
            'categories' => 'required',
            'tags'  => 'required',
            'body' => 'required',
        ]);
        $image = $request->file('image');
        $slug = Str::slug($request->title);

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('post')) {
                Storage::disk('public')->makeDirectory('post');
            }

            if (Storage::disk('public')->exists('category/' . $post->image)) {
                Storage::disk('public')->delete('category/' . $post->image);
            }
            $post_image = Image::make($image)->resize(1600, 1066)->stream();
            Storage::disk('public')->put('post/' . $imageName, $post_image);
        } else {
            $imageName = $post->image;
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if (isset($request->status)) {
            $post->status = true;
        } else {
            $post->status = false;
        }
        $post->approve = true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post successfully updated!', 'Success');
        return redirect()->route('author.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Post::find($id);
        if($post->user_id != Auth::id()){
            Toastr::error('You are not authorized to access this post', 'Error');
            return redirect()->route('author.post.index');
        }
        if ($post) {
            if (Storage::disk('public')->exists('post/' . $post->image)) {
                Storage::disk('public')->delete('post/' . $post->image);
            }
            $post->categories()->detach();
            $post->tags()->detach();
            $post->delete();
            Toastr::success('Post successfully deleted :)', 'Success');
            return redirect()->back();
        }
    }
}
