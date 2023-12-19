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
    public function index(Note $note)
    {
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
        // 直前にアクセスしたリンクに戻るための$testamentデータ
        $testamentValue = $request->query('ids');
        $last_selected_testament = Testament::where('id', $testamentValue)->first();

        // Sessionにリクエストデータtestament_arrayを保存する処理
        $selected_testaments = $request->query('ids', []); // リクエストデータからtestament_arrayを取得
        $existing_testaments = session('ids', []); // Sessionに保存されている既存のtestament_arrayを取得
        
        $merged_testaments = array_merge($existing_testaments, $selected_testaments); // 新しい選択されたtestamentsを既存のtestament_arrayに追加
        $unique_testaments = array_unique($merged_testaments); // 重複を除いたユニークな値のみを取得
        session(['ids' => $unique_testaments]); // Sessionに更新したtestament_arrayを保存
        
        $all_session_data = session('ids', []); //デバックのためのデータ
    
        // 更新したtestament_arrayを使ってTestamentモデルからデータを取得
        $testaments = Testament::whereIn('id', $unique_testaments)->get();
    
        $public_value = 'true';
    
        return view('notes.create')->with([
            'public_value' => $public_value,
            'tags' => $tag->get(),
            'all_session_data' => $all_session_data,
            'testaments' => $testaments,
            'last_selected_testament' => $last_selected_testament,
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
         
         session()->forget('ids'); // 既存のtestament_arrayを初期化
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
