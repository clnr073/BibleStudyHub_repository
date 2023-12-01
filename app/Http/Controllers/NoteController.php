<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Testament;
use App\Http\Requests\NoteRequest;

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
     * ノート詳細画面
     * 特定idのノートを表示する
     * 
     * $param Object Note
     * return Response note view
     */
     public function show(Note $note)
     {
         return view('notes.show')->with(['note' => $note]);
     }
    
    /**
     * ノート作成画面
     * $valueは、ラジオボタンのchecked属性を動的に制御するために用意
     */
    public function create(Tag $tag, Testament $testament)
    {
        $value = 'true';
        
        return view('notes.create')->with(['value' => $value, 'tags'=>$tag->get(), 'testaments'=>$testament->get()]);
    }
    
    /**
     * リクエストされたデータをnotesテーブルにinsertする
     */
     public function store(Note $note, NoteRequest $request)
     {
         $input_note = $request['note'];
         $input_testaments = $request->testaments_array;
         $input_tags = $request->tags_array;
         
         $input_note += ['user_id' => $request->user()->id]; // user():requestを送信したユーザーの情報を取得するRequestメソッド
         $note->fill($input_note)->save();
         // attachメソッドを使って中間テーブルにデータを保存
         $note->testaments()->attach($input_testaments);
         $note->tags()->attach($input_tags);
         return redirect(route('notes.index'));
     }
}
