<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{
    // Phương thức để duyệt bài viết
    public function browse()
    {
        $posts = Post::where('status', 'pending')->paginate(5);
        return view('admin.post.browse', compact('posts'));
    }
    public function feature(Request $request, $id)
    {
        $post = Post::find($id);
        $post->is_featured = $post->is_featured == 1 ? 0 : 1;

        $post->save();

        return Redirect::back();
    }
    public function status(Request $request, $id)
    {
        $post = Post::find($id);
        $post->status = $request->status;
        $post->save();

        return Redirect::back()->with('success', 'Cập nhật trạng thái thành công');
    }
}
