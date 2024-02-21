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
     public function index(Volume $volume, Request $request)
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
    public function displayVolumeWithContents($volume ,Testament $testament)
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
    public function displayChapterWithContents($volume, $chapter, Testament $testament)
    {
        $contents = $testament->where([
            ['volume_id', $volume],
            ['chapter', $chapter],
            ])->get();
        
        $chapter_set = $contents->first();
        
        $selected_testaments = session('testament_array', []); //Sessionからすでに選ばれた配列を取得
        $testament_id = collect($selected_testaments); // Collectionに変換
        
        // 最小のchapterを取得
        $earliest_chapter = $testament->getChapter($volume, false);
        
        // 最大のchapterを取得
        $latest_chapter = $testament->getChapter($volume);
        
        //インスタンスのvolume_id未満のvolume_idの中で最新のchapterを取得                         
        $previous_volume_latest_chapter = $testament->getPreviousVolumeLatestChapter($volume);
        
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
            'volume' => $volume,
            'chapter' => $chapter,
            'testaments' => $contents,
            'chapter_set' => $chapter_set,
            'testament_id' => $testament_id,
            'latest_chapter' => $latest_chapter, 
            'earliest_chapter' => $earliest_chapter,
            'previous_volume_latest_chapter' => $previous_volume_latest_chapter,
            'edit_note_id' => $edit_note_id ?? null,
            'comment_create_note_id' => $comment_create_note_id ?? null,
            'comment_edit_ids' => $comment_edit_ids ?? null,
            'all_session_data' => $all_session_data,
            ]);
    }
}
