<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testament;
use App\Models\Volume;

class TestamentController extends Controller
{
    /**
     * 聖書一覧画面
     */
     public function showVolume(Volume $volume, Request $request)
     {
         if ($request->has('comment_create')) {
             $note_id = $request->query('comment_create');
             if (!session()->has('comment_creating')) {
                session(['comment_creating' => $note_id]);
            }
         }
         
         return view('testaments.index')->with(['volumes' => $volume->get()]);
     }
     
    /**
     * volumeの章一覧を表示
     */
    public function showChapter($volume ,Testament $testament)
    {
        $chapters = $testament->where('volume_id', $volume)->groupBy('chapter')->pluck('chapter');
        
        $volume_title = Volume::where('id', $volume)->first();
        
        return view('testaments.volume')->with(['volume' => $volume, 'chapters' => $chapters, 'volume_title' => $volume_title]);
    }
     
    /**
     * 聖書詳細画面
     * 
     * @param object Testament
     * @return Response testament.show view
     */
    public function showSection($volume, $chapter, Testament $testament)
    {
        $contents = $testament->where([
            ['volume_id', $volume],
            ['chapter', $chapter],
            ])->get();
        
        // volumeタイトルとchapter表示用に1つレコードを取得
        $chapter_set = $contents->first();
        
        // ページ判定のため、volumeの最大のchapterを取得
        $current_volume_max_chapter = $testament->where('volume_id', $volume)->max('chapter');
        $previous_volume_max_chapter = $testament->where('volume_id', $volume - 1)->max('chapter');

        $selected_testaments = session('testament_array', []); //Sessionからすでに選ばれた配列を取得
        $testament_id = collect($selected_testaments); // Collectionに変換
        
        // ノートまたはコメントの作成・編集中であれば、noteのidを取得
        if(session()->has('note_editing')) {
            $edit_note_id = session('note_editing', []);
        }
        
        if (session()->has('comment_creating')) {
            $comment_create_note_id = session('comment_creating', []);
        }
        
        if (session()->has('comment_editing')) {
            $comment_edit_ids = session('comment_editing', []);
        }
        
        // セッション内のすべてのデータを取得する（デバック)
        $all_session_data = session()->all();
        
        return view('testaments.show')->with([
            'volume' => intval($volume),
            'chapter' => intval($chapter),
            'testaments' => $contents,
            'chapter_set' => $chapter_set,
            'testament_id' => $testament_id,
            'edit_note_id' => $edit_note_id ?? null,
            'comment_create_note_id' => $comment_create_note_id ?? null,
            'comment_edit_ids' => $comment_edit_ids ?? null,
            'all_session_data' => $all_session_data,
            'current_volume_max_chapter' => $current_volume_max_chapter,
            'previous_volume_max_chapter' => $previous_volume_max_chapter,
            ]);
    }
}
