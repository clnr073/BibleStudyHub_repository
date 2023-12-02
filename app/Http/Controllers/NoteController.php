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
     * $public_valueは、ラジオボタンのchecked属性を動的に制御するために用意
     */
    public function create(Tag $tag, Testament $testament)
    {
        $public_value = 'true';
        
        return view('notes.create')->with([
            'public_value' => $public_value,
            'tags' => $tag->get(),
            'testaments' => $testament->get(),
            ]);
    }
    
    /**
     * ノート保存処理
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
     
     /**
      * ノート編集画面
      */
      public function edit(Note $note, Tag $tag, Testament $testament)
      {
          $public_value = $note->public;
          // pluckメソッドでidカラムの値を抽出
          $testament_id = $note->testaments->pluck('id');
          $tag_id = $note->tags->pluck('id');
          
          return view('notes.edit')->with([
              'public_value' => $public_value,
              'testament_id' => $testament_id,
              'tag_id' => $tag_id,
              'note' => $note,
              'tags' => $tag->get(),
              'testaments' => $testament->get(),
              ]);
      }
      
     /**
      * ノート更新処理
      */
      public function update()
      {
          //
      }
}
