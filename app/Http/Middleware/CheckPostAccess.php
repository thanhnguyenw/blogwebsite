<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPostAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $postId = $request->route('post');
        if (!$this->canAccessPost($postId)) {
            return abort(403); // Ngăn chặn truy cập nếu không được phép
        }
        return $next($request);
    }

    private function canAccessPost($postId)
{
    // Kiểm tra xem user có quyền xem bài post không
    // Ví dụ: chỉ cho phép người tạo bài post và người đăng nhập xem
    $user = Auth::user();
    if ($user && $user->posts->contains('id', $postId)) {
        return true;
    }

    return false;
}
}
