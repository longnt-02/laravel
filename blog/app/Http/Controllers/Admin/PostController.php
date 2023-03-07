<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index() {
        $posts = Post::paginate(20);
        return view('admin.post.list', compact('posts'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.post.create',compact('categories'));
    }

    public function store(Request $request) {
        // dd($request);
        $this->validate($request, 
            [
                'title' => 'required',
                'description' => 'required',
                'content' => 'required',
                'image' => 'required',
                'category_id' => 'required',
            ],
        );

        $slug = Str::slug($request->name);

        $checkSlug = Post::where('slug', $slug)->first();

        while($checkSlug) {
            $slug = $checkSlug->slug . Str::random(2);
        }

        if($request->hasFile('image')){
            $file = $request->file('image');
            $name_file = $file->getClientOriginalName();

            $extention = $file->getClientOriginalExtension();

            $image = Str::random(5) . "_" . $name_file;
            if( strcasecmp($extention, 'jpg') === 0
                || strcasecmp($extention, 'png') === 0
                || strcasecmp($extention, 'jepg') === 0
            ){
                while(file_exists("image/post/" . $image)){
                    $image = Str::random(5) . "_" . $name_file;
                }

                $file->move('image/post', $image);
            }
        }

        Post::create([
            'title'=> $request->tilte,
            'description'=> $request->description,
            'content'=> $request->get('content'),
            'image'=> $image,
            'view_counts'=> 0,
            'user_id'=> 1, //Auth::id()
            'new_post'=> $request->new_post ? 1 : 0,
            'slug'=> $slug,
            'category_id'=> $request->category_id,
            'highlight_post'=> $request->highlight_post ? 1 : 0,
        ]);

        // Post::create($request->all());

        return redirect()->route('admin.post.index')->with('success', 'Create successfully');
    }

    public function edit($id) {
        $post = Post::find();
        $categories = Category::all();

        return view('admin.post.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, 
            [
                'title' => 'required',
                'description' => 'required',
                'content' => 'required',
                'category_id' => 'required',
            ],
        );

        $slug = Str::slug($request->name);

        $checkSlug = Post::where('slug', $slug)->first();

        while($checkSlug) {
            $slug = $checkSlug->slug . Str::random(2);
        }

        if($request->hasFile('image')){
            $file = $request->file('image');
            $name_file = $file->getClientOriginalName();

            $extention = $file->getClientOriginalExtension();

            $image = Str::random(5) . "_" . $name_file;
            if( strcasecmp($extention, 'jpg') === 0
                || strcasecmp($extention, 'png') === 0
                || strcasecmp($extention, 'jepg') === 0
            ){
                while(file_exists("image/post/" . $image)){
                    $image = Str::random(5) . "_" . $name_file;
                }

                $file->move('image/post', $image);
            }
        }

        $post = Post::find($id);
        $post->update([
            'title'=> $request->tilte,
            'description'=> $request->description,
            'content'=> $request->get('content'),
            'image'=> isset($image) ? $image : $post->image,
            'new_post'=> $request->new_post ? 1 : 0,
            'slug'=> $slug,
            'category_id'=> $request->category_id,
            'highlight_post'=> $request->highlight_post ? 1 : 0,
        ]);

        // Post::create($repuest->all());

        return redirect()->route('admin.post.index', $id)->with('success', 'Update successfully');

    }

    public function delete($id) {
        Post::find($id)->delete();
        return redirect()->route('admin.post.index', $id)->with('success', 'Delete successfully');
    }
}
