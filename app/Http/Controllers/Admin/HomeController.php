<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $startDate = now()->subMonth(); // Lấy ngày bắt đầu là thời điểm 1 tháng trước
        $endDate = now(); // Lấy ngày hiện tại
        $approvedCount = Post::where('status', 'approved')->count();
        $pendingCount = Post::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count();

        return view('admin.dashboard', compact('approvedCount', 'pendingCount'));
    }
}
