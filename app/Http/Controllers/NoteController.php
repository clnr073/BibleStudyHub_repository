<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Testament;
use App\Models\Comment;
use App\Http\Requests\NoteRequest;
use Illuminate\Support\Facades\Auth;
use Cloudinary;

class NoteController extends Controller
{
    /**
     * ノート一覧画面
     */
    public function index(Request $request, Note $note, Tag $tag)
    {
        if ($request->has('cancel_note_take')) {
            // sessionからvolumeキー、chapterキーの値を取得
            $volumes = session('volume', []);
            $chapters = session('chapter', []);
            
            $keys_to_delete = []; // 削除するセッションキーの配列
            
            foreach ($volumes as $volume) {
                foreach ($chapters as $chapter) {
                    $key = 'ids_' . $volume . '_' . $chapter;
                    $keys_to_delete[] = $key;
                }
            }
            
            $unique_keys_to_delete = array_unique($keys_to_delete); // 重複するキーを削除
            // 配列に保存されたセッションキーを一括で削除
            session()->forget($unique_keys_to_delete);
            session()->forget([
                 'comment_editing',
                 'comment_creating',
                 'note_editing',
                 'volume',
                 'chapter',
                 'testament_array',
             ]);
        }
        
        $user_id = Auth::id();
        
        if ($request->has('tag')) {
            $tag_id = $request->query('tag');
            $notes_by_tag = $note->getByTag($user_id, $tag_id);
            return view('notes.index')->with(['notes' => $notes_by_tag, 'tags' => $tag->getPaginateByLimit($user_id), 'user_id' => $user_id]);
        } else {
            return view('notes.index')->with(['notes' => $note->getPaginateByLimit($user_id), 'tags' => $tag->getPaginateByLimit($user_id), 'user_id' => $user_id]);
        }
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
         // idを昇順で並べ替え
         $testaments_query_builder = $note->testaments->sortBy('id');
         
         // volume_id をキーとし、その下に chapter をキーとした testaments の多重連想配列
         $testaments_by_volume_and_chapter = $testaments_query_builder->groupBy('volume_id')->map(function ($testaments) {
             return $testaments->groupBy('chapter');
         });
         
         $user_id = Auth::id();
        
         return view('notes.show')->with([
             'testaments_by_volume_and_chapter' => $testaments_by_volume_and_chapter,
             'note' => $note,
             'user_id' => $user_id,
             ]);
     }
    
    /**
     * ノート作成画面
     * $public_valueは、ラジオボタンのchecked属性を動的に制御するために用意
     */
    public function create(Request $request, Tag $tag)
    {   
        // クエリパラメータidsが送られた場合の処理
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
            $selected_testaments = $request->query('ids', []); // リクエストデータからidsを取得
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
    
        //sessionに保存されたtestament_arrayの値と等しいtestamentsから取得
        $testaments = Testament::whereIn('id', session('testament_array', []))->get();
        
        // volume_id をキーとし、その下に chapter をキーとした testaments の多重連想配列
        $testaments_by_volume_and_chapter = $testaments->groupBy('volume_id')->map(function ($testaments) {
             return $testaments->groupBy('chapter');
         });
        
        $public_value = 'true';
        
        // セッション内のすべてのデータを取得する（デバック)
        $all_session_data = session()->all();
        
        $user_id = Auth::id();
    
        return view('notes.create')->with([
            'public_value' => $public_value,
            'tags' => $tag->getPaginateByLimit($user_id),
            'testaments' => $testaments,
            'testaments_by_volume_and_chapter' => $testaments_by_volume_and_chapter,
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
         $input_testaments = $request->testaments_array;
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
        
         $keys_to_delete = []; // 削除するセッションキーの配列
        
         foreach ($volumes as $volume) {
             foreach ($chapters as $chapter) {
                 $key = 'ids_' . $volume . '_' . $chapter;
                 $keys_to_delete[] = $key;
             }
         }
        
         $unique_key_to_delete = array_unique($keys_to_delete); // 重複するキーを削除
         // 配列に保存されたセッションキーを一括で削除
         session()->forget($unique_key_to_delete);
         session()->forget([
             'comment_editing',
             'comment_creating',
             'note_editing',
             'volume',
             'chapter',
             'testament_array',
             ]);
         
         return redirect(route('notes.index'));
     }
     
     /**
      * ノート編集画面
      */
      public function edit(Note $note, Tag $tag, Request $request)
      {
          // sessionに編集中のデータがない場合の処理
          if (!session()->has('note_editing')) {
              // 編集するノートのtestamentsをsessionに保存する
              // 特定の$noteに関連付けられたtestamentsからvolume_idとchapterの一意な値を抽出
              // それらの組み合わせごとに一致するtestamentsのidをセッションに保存する
              $note_id = $note->id;
              session(['note_editing' => $note_id]); // sessionに編集しているnoteのidを保存
              
              $note_volumes = $note->testaments->pluck('volume_id')->toArray(); // $noteの持つtestamentsのvolume_idを配列で取得
              $note_chapters = $note->testaments->pluck('chapter')->toArray(); // $noteの持つtestamentsのchapterを配列で取得
              
              // 重複のない配列に変換
              $unique_note_volumes = array_unique($note_volumes);
              $unique_note_chapters = array_unique($note_chapters);
              
              // それぞれをsessionの指定したキーに保存
              session(['volume' => $unique_note_volumes]);
              session(['chapter' => $unique_note_chapters]);
              
              // volume_idとchapterごとにtestamentsのidを振り分けて保存する
              // 合わせて、すべてのtestamentsのidをtestament_arrayに保存する
              $note_testaments = $note->testaments; //noteの持つtestamentsを取得
              
              $note_testament_array = []; //すべてのidを代入する配列
              
              foreach ($unique_note_volumes as $note_volume) {
                  foreach ($unique_note_chapters as $note_chapter) {
                      // 変数に基づいてキーを生成
                      $note_session_key = 'ids_' . $note_volume . '_' .$note_chapter;
                      // noteの持つtestamentから指定した条件でidを取得して、配列に変換
                      $filtered_id = $note_testaments->where('volume_id', $note_volume)
                                             ->where('chapter', $note_chapter)
                                             ->pluck('id')
                                             ->toArray();
                      
                      if (!empty($filtered_id)) {
                          session([$note_session_key => $filtered_id]); //idをsessionの特定のキーに保存
                      }
                      $note_testament_array = array_merge($note_testament_array, $filtered_id);
                  }
              }
              
              session(['testament_array' => $note_testament_array]); //testament_arrayにすべてのidを保存
          }
          
          // クエリパラメータidsが送られた場合の処理
          if ($request->has('ids')) {
                // 直前にアクセスしたリンクに戻るために$testamentデータからvolumeとchapterを取得
                $testament_value = $request->query('ids');
                $last_selected_testament = Testament::where('id', $testament_value)->first();
                $volume = [$last_selected_testament->volume->id];
                $chapter = [$last_selected_testament->chapter];
    
                // sessionに$volumeを保存する処理
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
                $selected_testaments = $request->query('ids', []); // リクエストデータからidsを取得
                session([$session_key => $selected_testaments]); // sessionの指定したキーにリクエストデータを上書き
                
                //ページごとに保存されたidsを取り出し、1つの配列に保存する
                // volumeとchapterのidを使用して処理
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
          
          //sessionに保存されたtestament_arrayの値と等しいtestamentsから取得
          $testaments = Testament::whereIn('id', session('testament_array', []))->get();
          
          // volume_id をキーとし、その下に chapter をキーとした testaments の多重連想配列
          $testaments_by_volume_and_chapter = $testaments->groupBy('volume_id')->map(function ($testaments) {
             return $testaments->groupBy('chapter');
          });
            
          $public_value = $note->public;
          
          $user_id = Auth::id();
          
          // pluckメソッドでidカラムの値を抽出
          $tag_id = $note->tags->pluck('id');
          
          return view('notes.edit')->with([
              'public_value' => $public_value,
              'tag_id' => $tag_id,
              'note' => $note,
              'tags' => $tag->getPaginateByLimit($user_id),
              'testaments' => $testaments,
              'testaments_by_volume_and_chapter' => $testaments_by_volume_and_chapter,
              'last_selected_testament' => $last_selected_testament ?? null,
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
          
          if ($request->file('image')) { //画像ファイルが送られたときだけ処理が実行される
            //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
            $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $input_note += ['image_url' => $image_url];
         }
         
          $input_note += ['user_id' => $request->user()->id]; // user():requestを送信したユーザーの情報を取得するRequestメソッド
          $note->fill($input_note)->save();
          
          // 関連データを更新するために sync() メソッドを使用する
          $note->testaments()->sync($input_testaments);
          $note->tags()->sync($input_tags);
          
          // sessionに保存していたデータを消去する
          // sessionからvolumeキー、chapterキーの値を取得
          $volumes = session('volume', []);
          $chapters = session('chapter', []);
        
          $keys_to_delete = []; // 削除するセッションキーの配列
        
          foreach ($volumes as $volume) {
              foreach ($chapters as $chapter) {
                  $key = 'ids_' . $volume . '_' . $chapter;
                  $keys_to_delete[] = $key;
              }
          }
        
          $unique_key_to_delete = array_unique($keys_to_delete); // 重複するキーを削除
          // 配列に保存されたセッションキーを一括で削除
          session()->forget($unique_key_to_delete);
          session()->forget([
             'comment_editing',
             'comment_creating',
             'note_editing',
             'volume',
             'chapter',
             'testament_array',
             ]);
          
          return redirect('/notes/' . $note->id);
      }
      
      /**
       * ノート削除処理
       */
       public function delete(Note $note)
       {
           $note->delete(); // Modelクラスの関数delete
           return redirect(route('notes.index'));
       }
}