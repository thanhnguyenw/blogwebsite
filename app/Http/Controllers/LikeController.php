<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LikeController extends Controller
{
    public function addLike(Request $request, $postId)
    {

        $postId = $request->input('post_id');

        // Tìm bài viết theo post_id hoặc nếu không tìm thấy sẽ ném ra ngoại lệ (exception)
        $post = Post::findOrFail($postId);

        // Kiểm tra xem người dùng đã thích bài viết này trước đó chưa
        $existingLike = Like::where('user_id', $request->user()->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            // Nếu người dùng đã thích bài viết, xóa bỏ like này
            $existingLike->delete();
            return response()->json(['like' => false]);
        }

        // Người dùng chưa thích bài viết, tạo mới một like
        $like = new Like();
        $like->post_id = $postId;
        $like->user_id = Auth::user()->id;

        $like->save();

        // Trả về phản hồi với trạng thái like = true để cho biết người dùng đã like bài viết
        return response()->json(['like' => true]);
    }
    public function checkLike($postId)
    {
        $user = Auth::user();
        $post = Like::where('user_id', $user->id)->where('post_id', $postId)->first();
        if ($post) {
            return response()->json(['like' => true]);
        }
        return response()->json(['like' => false]);
    }
}
