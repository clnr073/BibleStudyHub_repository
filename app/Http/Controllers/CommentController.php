<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Testament;

class CommentController extends Controller
{
    public function store (Comment $comment, Testament $testament, Request $request)
    {
        $input_comment = $request['new_comment'];
        $input_testaments = $request->testaments_array;
        
        $input_comment += ['user_id' => $request->user()->id];
        $comment->fill($input_comment)->save();
        
        $comment->testaments()->attach($input_testaments);
        
        return redirect('/notes/' . $comment->note_id);
    }
}
