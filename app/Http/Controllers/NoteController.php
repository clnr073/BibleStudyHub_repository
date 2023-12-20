<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Testament;
use App\Models\Comment;
use App\Http\Requests\NoteRequest;
use Cloudinary;

class NoteController extends Controller
{
    /**
     * ノート一覧画面
     */
    public function index(Request $request, Note $note)
    {
        if ($request->has('cancel_notetake')) {
            // sessionからvolumeキー、chapterキーの値を取得
            $volumes = session('volume', []);
            $chapters = session('chapter', []);
            
            $key_to_delete = []; // 削除するセッションキーの配列
            
            foreach ($volumes as $volume) {
                foreach ($chapters as $chapter) {
                    $key = 'ids_' . $volume . '_' . $chapter;
                    $keys_to_delete[] = $key;
                }
            }
            
            $unique_key_to_delete = array_merge($keys_to_delete); // 重複するキーを削除
            // 配列に保存されたセッションキーを一括で削除
            session()->forget($unique_key_to_delete);
            session()->forget(['volume', 'chapter', 'testament_array']);
        }
        
        $notes = $note->get();
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
         $testaments_query_builder = $note->testaments;
         
         // volume_id をキーとし、その下に chapter をキーとした testaments の多重連想配列
         $testaments_by_volume_and_chapter = $testaments_query_builder->groupBy('volume_id')->map(function ($testaments) {
             return $testaments->groupBy('chapter');
         });
        
         /**
          * 各volume_id、chapterごとに、最初のsectionと最後のsectionを記録する処理
          */
         // リレーションシップを含むすべてのtestamentsを取得
         $loaded_testaments_with_volume = $note->testaments()->with('volume')->get();
        
         // chapterごとに最初のsectionと最後のsectionを格納する連想配列
         $section_info_by_volume = [];
         
         foreach ($loaded_testaments_with_volume as $testament) {
             $volume_id = $testament->volume->id;
             $chapter = $testament->chapter;
             $section = $testament->section;
             
             // $section_info_by_volumeに$volume_id、$chapterに関連づけられたエントリが存在するか確認
             if (!isset($section_info_by_volume[$volume_id][$chapter])) {
                 // 存在しないなら新しいvolume_id、$chapter用のエントリを作成
                 $section_info_by_volume[$volume_id][$chapter] = [
                     'first_section' => $section,
                     'last_section' => $section,
                     ];
             } else {
                 // 最小のsectionを更新
                 if ($section < $section_info_by_volume[$volume_id][$chapter]['first_section']) {
                     $section_info_by_volume[$volume_id][$chapter]['first_section'] = $section;
                 }
                 
                 // 最大のsectionを更新
                 if ($section > $section_info_by_volume[$volume_id][$chapter]['last_section']) {
                     $section_info_by_volume[$volume_id][$chapter]['last_section'] = $section;
                 }
             }
         }

         return view('notes.show')->with([
             'testaments_by_volume_and_chapter' => $testaments_by_volume_and_chapter,
             'section_info_by_volume' => $section_info_by_volume,
             'note' => $note]);
     }
    
    /**
     * ノート作成画面
     * $public_valueは、ラジオボタンのchecked属性を動的に制御するために用意
     */
    public function create(Request $request, Tag $tag)
    {   
        if ($request->has('ids')) {
            // 直前にアクセスしたリンクに戻るために$testamentデータからvolumeとchapterを取得
            $testament_value = $request->query('ids');
            $last_selected_testament = Testament::where('id', $testament_value)->first();
            $volume = [$last_selected_testament->volume->id];
            $chapter = [$last_selected_testament->chapter];

            // Sessionに$volumeを保存する処理
            $existing_volume = session('volume', []); // 既存のvolumeの配列を変数に格納
            $merged_volume = array_merge($existing_volume, $volume); // リクエストデータのvolume番号を既存のvolume配列に追加
            $unique_volume = array_unique($merged_volume); // 重複を除いたユニークな値のみを取得
            session(['volume' => $unique_volume]);
        
            // Sessionに$chapterを保存する処理
            $existing_chapter = session('chapter', []); // 既存のchapterの配列を変数に格納
            $merged_chapter = array_merge($existing_chapter, $chapter); // リクエストデータのchapterを既存のchapter配列に追加
            $unique_chapter = array_unique($merged_chapter); // 重複を除いたユニークな値のみを取得
            session(['chapter' => $unique_chapter]);
        
            // Sessionにリクエストデータidsを保存する処理
            $session_key = 'ids_' . $volume[0] . '_' . $chapter[0];
            $selected_testaments = $request->query('ids', []); // リクエストデータからtestament_arrayを取得
            session([$session_key => $selected_testaments]); // sessionの指定したキーにリクエストデータを上書き
            
            //ページたびに保存されたidsを取り出し、1つの配列に保存する
            // volume と chapter の ID を使用して処理
            $testament_array = [];
        
            $volumes = session('volume', []);
            $chapters = session('chapter', []);
            
            foreach ($volumes as $volume) {
                foreach ($chapters as $chapter) {
                    $key = 'ids_' . $volume . '_' . $chapter;
        
                    // session から値を取得し、処理を行う
                    $testament_per_key = session($key, []);
        
                    // $testament_per_key の値を $testament_array にマージ
                    $testament_array = array_merge($testament_array, $testament_per_key);
                }
            }
        
            // $testamentArray を testament_array キーで session に保存
            session(['testament_array' => $testament_array]);
        }
    
        // session から testament_array を取得して Testaments を取得
        $testaments = Testament::whereIn('id', session('testament_array', []))->get();
    
        $public_value = 'true';
        
        // セッション内のすべてのデータを取得する（デバック）
        $all_session_data = session()->all();
    
        return view('notes.create')->with([
            'public_value' => $public_value,
            'tags' => $tag->get(),
            'testaments' => $testaments,
            'session_testaments_data' => $testaments, //デバック
            'last_selected_testament' => $last_selected_testament ?? null,
            'all_session_data' => $all_session_data, //デバック
        ]);
    }
    
    /**
     * ノート保存処理
     * リクエストされたデータをnotesテーブルにinsertする
     */
     public function store(Note $note, NoteRequest $request)
     {
         $input_note = $request['note'];
         $input_testaments = $request->testament_array;
         $input_tags = $request->tags_array;
         
         if ($request->file('image')) { //画像ファイルが送られたときだけ処理が実行される
            //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
            $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $input_note += ['image_url' => $image_url];
         }
         
         $input_note += ['user_id' => $request->user()->id]; // user():requestを送信したユーザーの情報を取得するRequestメソッド
         $note->fill($input_note)->save();
         
         // attachメソッドを使って中間テーブルにデータを保存
         $note->testaments()->attach($input_testaments);
         $note->tags()->attach($input_tags);
         
         // sessionに保存していたデータを消去する
         // sessionからvolumeキー、chapterキーの値を取得
         $volumes = session('volume', []);
         $chapters = session('chapter', []);
        
         $key_to_delete = []; // 削除するセッションキーの配列
        
         foreach ($volumes as $volume) {
             foreach ($chapters as $chapter) {
                 $key = 'ids_' . $volume . '_' . $chapter;
                 $keys_to_delete[] = $key;
             }
         }
        
         $unique_key_to_delete = array_merge($keys_to_delete); // 重複するキーを削除
         // 配列に保存されたセッションキーを一括で削除
         session()->forget($unique_key_to_delete);
         session()->forget(['volume', 'chapter', 'testament_array']);
         
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
      public function update(Note $note, NoteRequest $request)
      {
          $input_note = $request['note'];
          $input_testaments = $request->testaments_array;
          $input_tags = $request->tags_array;
         
          $input_note += ['user_id' => $request->user()->id]; // user():requestを送信したユーザーの情報を取得するRequestメソッド
          $note->fill($input_note)->save();
          
          // 関連データを更新するために sync() メソッドを使用する
          $note->testaments()->sync($input_testaments);
          $note->tags()->sync($input_tags);
          
          return redirect('/notes/' . $note->id);
      }
      
      /**
       * ノート削除処理
       */
       public function delete(Note $note)
       {
           $note->delete(); //Modelクラスの関数delete
           return redirect(route('notes.index'));
       }
}
