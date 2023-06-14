<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::all();
        // my post and pagination simple
        $posts = Post::where('user_id', Auth::user()->id)->paginate(5);
        foreach ($posts as $post) {
            $selectedCategories = $post->categories()->pluck('name')->implode(', ');
            $post->selectedCategories = $selectedCategories;
        }

        return view('client.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('client.post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'short_content' => 'required|max:255',
            'categories' => 'required|array',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $thumbnail = $request->file('thumbnail');
        $newName = time() . "." . $thumbnail->getClientOriginalExtension();

        $blog = new Post();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->short_content = $request->short_content;
        $blog->user_id = Auth::user()->id;
        $blog->thumbnail = $newName;
        $blog->save();
        // move file thumbnail to uploads
        $thumbnail->move(public_path('uploads'), $newName);
        $selectedCategories = $request->input('categories', []);
        $blog->categories()->sync($selectedCategories);

        session()->flash('success', 'Dữ liệu đã được tạo thành công.');
        return redirect()->route('post.index');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->upload;
            $newName = time() . "." . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $newName);
            $url = asset("uploads/" . $newName);


            return response()->json(['filename' => $newName, "uploaded" => 1, 'url' => $url]);
        }
        // if ($request->hasFile('upload')) {
        //     $file = $request->upload;
        //     $newName = time() . "." . $file->getClientOriginalExtension();
        //     $file->move(public_path('uploads'), $newName);

        //     // Thay đổi kích thước hình ảnh
        //     $image = Image::make(public_path('uploads/' . $newName));
        //     $image->resize(100, null, function ($constraint) {
        //         $constraint->aspectRatio();
        //         $constraint->upsize();
        //     });
        //     $image->save();

        //     $url = asset("uploads/" . $newName);
        //     return response()->json(['filename' => $newName, "uploaded" => 1, 'url' => $url]);
        // }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $post = Post::find($id);
        $user = $post->user;

        return view('client.post.show', compact('post', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);
        $categories = Category::all();
        $selectedCategories = $post->categories->pluck('id')->toArray();

        return view('client.post.edit', compact('post', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'short_content' => 'required|max:255', // Giới hạn độ dài tối đa của short_content là 255 ký tự
            'categories' => 'required|array',
        ]);

        $blog = Post::find($id);
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = Auth::user()->id;
        $blog->status = 'pending';

        if ($request->hasFile('thumbnail')) {
            $request->validate([
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Kiểm tra tính hợp lệ của file thumbnail
            ]);

            // Xóa file đã tải lên trước đó
            unlink(public_path('uploads/' . $blog->thumbnail));

            $thumbnail = $request->file('thumbnail');
            $newName = time() . "." . $thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path('uploads'), $newName);
            $blog->thumbnail = $newName;
        }

        $blog->save();

        $selectedCategories = $request->input('categories', []);
        $blog->categories()->sync($selectedCategories); // Cập nhật danh mục liên quan đến bài viết

        session()->flash('success', 'Dữ liệu đã được sửa thành công.');
        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Post::find($id);
        $blog->delete();
        return redirect()->route('post.index');
    }

    public function showByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $posts = $category->posts()->where('status', 'approved')->paginate(10);

        return view('client.search', compact('posts', 'category'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $posts = Post::where('title', 'like', '%' . $keyword . '%')
            ->where('status', 'approved')
            ->with('categories')
            ->paginate(10);
        return view('client.search', compact('posts'));
    }

    public function detail($id)
    {
        $post = Post::findOrFail($id);
        $comments = $post->comments();
        // Lấy danh sách tất cả các danh mục của các bài viết
        $categories = $post->categories()->pluck('categories.id');

        // Lấy các bài viết liên quan theo danh mục
        $relatedPosts = Post::whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('categories.id', $categories);
        })
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->where('status', 'approved')
            ->limit(6)
            ->get();

        return view('client.post.detail', compact('post', 'comments', 'relatedPosts'));
    }
}
