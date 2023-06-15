<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // get newest post
        $posts_slide = Post::where('is_featured', true)
        ->latest()
        ->where('status', 'approved')
        ->take(5)
        ->get();

        $posts = Post::orderBy('created_at', 'desc')->where('status', 'approved')->paginate(4);

        
        return view('client.home', compact('posts', 'posts_slide'));
    }
}
