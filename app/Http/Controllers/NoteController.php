<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * ノート一覧画面
     */
    public function index(Note $note)
    {
        return view('notes.index')->with(['notes' => $note->get()]);
    }
    
    /**
     * ノート作成画面
     */
    public function create(Note $note)
    {
        return view('notes.create');
    }
    
    /**
     * リクエストされたデータをnotesテーブルにinsertする
     */
     
     public function store(Request $request, Note $note)
     {
         $input = $request['post'];
         $input += ['user_id' => $request->user()->id];
         $post = fill($input)->save();
         return redirect(route('notes.index'));
     }
}
