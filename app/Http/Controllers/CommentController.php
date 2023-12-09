<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Testament;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    /**
     * コメント一覧表示
     */
    public function index($note, Comment $comment, Testament $testament)
    {
        // 特定のnote_idに関連するコメントを取得するクエリを実行
        $comments = $comment::where('note_id', $note)->get();
        
        $comment_testament = $testament->get();
        
        return view('notes.comments.index')->with(['note_id' => $note, 'comments' => $comments, 'comment_testaments' => $comment_testament]);
    }
    
    /**
     * コメント詳細画面
     */
    public function show($comment)
    {
        //
    }
     
    /**
     * コメント保存処理
     */
    public function store(Comment $comment, Testament $testament, CommentRequest $request)
    {
        $input_comment = $request['new_comment'];
        $input_testaments = $request->testaments_array;
        
        $input_comment += ['user_id' => $request->user()->id];
        $comment->fill($input_comment)->save();
        
        $comment->testaments()->attach($input_testaments);
        
        return redirect('/notes/' . $comment->note_id . '/comments');
    }
    
    /**
     * コメント編集処理
     */
    public function edit($note, $comment, Testament $testament)
    {
        $edit_comment = Comment::find($comment);
        
        $testament_id = $edit_comment->testaments->pluck('id');
        
        return view('notes.comments.edit')->with([
            'note_id' => $note,
            'comment' => $edit_comment,
            'testament_id' => $testament_id,
            'testaments' => $testament->get(),
            ]);
    }
    
    /**
     * コメント更新処理
     */
    public function update($note, $comment, Testament $testament, CommentRequest $request)
    {
        $input_comment = $request['new_comment'];
        $input_testaments = $request->testaments_array;
        
        $input_comment += ['user_id' => $request->user()->id];
        
        $edited_comment = Comment::find($comment);
        $edited_comment->fill($input_comment)->save();
        
        $edited_comment->testaments()->sync($input_testaments);
        
        return redirect('/notes/' . $note . '/comments');
    }
    
    /**
     * コメント削除処理
     */
    public function delete($note, $comment)
    {
        $comment_model = Comment::findOrFail($comment);
        
        $comment_model->delete();
        return redirect('/notes/' . $note . '/comments');
    }
}
