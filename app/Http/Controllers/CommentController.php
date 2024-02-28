<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Testament;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント一覧表示
     */
    public function index($note, Comment $comment, Request $request)
    {
        if ($request->has('cancel_comment_take')) {
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
        
        // 特定のnote_idに関連するコメントを取得するクエリを実行
        $comments = Comment::where('note_id', $note)->orderBy('created_at', 'asc')->paginate(5);

        // volume,chapterごとにtestamentsをグルーピング
        foreach ($comments as $comment) {
            $comments_testaments = $comment->testaments;
        
            $grouped_testaments = $comments_testaments->groupBy('volume_id')->map(function ($testaments) {
                return $testaments->groupBy('chapter');
            });
        
            // $commentに直接$grouped_testamentsを格納する
            $comment->grouped_testaments = $grouped_testaments;
        }

        // セッション内のすべてのデータを取得する（デバック)
        $all_session_data = session()->all();
        
        $user_id = Auth::id();
        
        return view('notes.comments.index')->with([
            'note_id' => $note,
            'comments' => $comments,
            'testaments' => $testaments,
            'testaments_by_volume_and_chapter' => $testaments_by_volume_and_chapter,
            'last_selected_testament' => $last_selected_testament ?? null,
            'all_session_data' => $all_session_data,
            'user_id' => $user_id,
            ]);
    }
     
    /**
     * コメント保存処理
     */
    public function store(Comment $comment, CommentRequest $request)
    {
        $input_comment = $request['new_comment'];
        $input_testaments = $request->testaments_array;
        
        $input_comment += ['user_id' => $request->user()->id];
        $comment->fill($input_comment)->save();
        
        $comment->testaments()->attach($input_testaments);
        
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
        
        return redirect('/notes/' . $comment->note_id . '/comments');
    }
    
    /**
     * コメント編集処理
     */
    public function edit($note, $comment, Request $request)
    {
        $edit_comment = Comment::find($comment);
        
        if (session()->has('comment_creating')) {
            session()->forget('comment_creating');
        }
        
        // sessionに編集中のデータがない場合の処理
          if (!session()->has('comment_editing')) {
              // 編集するコメントのtestamentsをsessionに保存する
              // 特定の$commentに関連付けられたtestamentsからvolume_idとchapterの一意な値を抽出
              // それらの組み合わせごとに一致するtestamentsのidをセッションに保存する
              $value = [$note, $comment];
              session(['comment_editing' => $value]); // sessionに編集しているnote、commentのidを保存
              
              $comment_volumes = $edit_comment->testaments->pluck('volume_id')->toArray(); // $commentの持つtestamentsのvolume_idを配列で取得
              $comment_chapters = $edit_comment->testaments->pluck('chapter')->toArray(); // $commentの持つtestamentsのchapterを配列で取得
              
              // 重複のない配列に変換
              $unique_comment_volumes = array_unique($comment_volumes);
              $unique_comment_chapters = array_unique($comment_chapters);
              
              // それぞれをsessionの指定したキーに保存
              session(['volume' => $unique_comment_volumes]);
              session(['chapter' => $unique_comment_chapters]);
              
              // volume_idとchapterごとにtestamentsのidを振り分けて保存する
              // 合わせて、すべてのtestamentsのidをtestament_arrayに保存する
              $comment_testaments = $edit_comment->testaments; // $commentの持つtestamentsを取得
              
              $comment_testament_array = []; //すべてのidを代入する配列
              
              foreach ($unique_comment_volumes as $comment_volume) {
                  foreach ($unique_comment_chapters as $comment_chapter) {
                      // 変数に基づいてキーを生成
                      $comment_session_key = 'ids_' . $comment_volume . '_' .$comment_chapter;
                      // $commentの持つtestamentから指定した条件でidを取得して、配列に変換
                      $filtered_id = $comment_testaments->where('volume_id', $comment_volume)
                                             ->where('chapter', $comment_chapter)
                                             ->pluck('id')
                                             ->toArray();
                      
                      if (!empty($filtered_id)) {
                          session([$comment_session_key => $filtered_id]); //idをsessionの特定のキーに保存
                      }
                      $comment_testament_array = array_merge($comment_testament_array, $filtered_id);
                  }
              }
              
              session(['testament_array' => $comment_testament_array]); //testament_arrayにすべてのidを保存
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
        
        return view('notes.comments.edit')->with([
            'note_id' => $note,
            'comment' => $edit_comment,
            'testaments' => $testaments,
            'testaments_by_volume_and_chapter' => $testaments_by_volume_and_chapter,
            'last_selected_testament' => $last_selected_testament ?? null,
            ]);
    }
    
    /**
     * コメント更新処理
     */
    public function update($note, $comment, CommentRequest $request)
    {
        $input_comment = $request['new_comment'];
        $input_testaments = $request->testaments_array;
        
        $input_comment += ['user_id' => $request->user()->id];
        
        $edited_comment = Comment::find($comment);
        $edited_comment->fill($input_comment)->save();
        
        $edited_comment->testaments()->sync($input_testaments);
        
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
