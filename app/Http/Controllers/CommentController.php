<?php namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $comment = new Comment();
        $comment->text = $request->input('comment_text');
        $comment->standard_field_id = $request->input('fieldid');
        $comment->user_id = Auth::user()->id;
        $comment->save();
        
        return redirect()->back();
    }
}
