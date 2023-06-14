<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function reply(Request $request,$commentId)
    {
        $request->validate([
            'content' => 'required',
        ]);
        $comment = new Reply();
        $comment->content = $request->content;
        $comment->user_id = Auth::user()->id;
        $comment->comment_id = $commentId;
        $comment->save();
        return redirect()->back()->withAnchor("comment-$commentId");
    }
}
