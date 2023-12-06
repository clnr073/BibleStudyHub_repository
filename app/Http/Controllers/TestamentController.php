<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testament;

class TestamentController extends Controller
{
    
    /**
     * 聖書一覧画面
     * 
     * @param object Testament
     * @return Response testament.show view
     */
    
    public function displayChapterWithContents(Testament $testament, $volume_id = 1, $chapter = 1)
    {
        $contents = $testament->where([
            ['volume_id', $volume_id],
            ['chapter', $chapter],
            ])->get();
        
        $chapter_set = $contents->first();
        
        // 最小のchapterを取得
        $earliest_chapter = $testament->getChapter($volume_id, false);
        
        // 最大のchapterを取得
        $latest_chapter = $testament->getChapter($volume_id);
        
        //インスタンスのvolume_id未満のvolume_idの中で最新のchapterを取得                           
        $previous_volume_latest_chapter = $testament->getPreviousVolumeLatestChapter($volume_id);
        
        return view('testaments.index')->with([
            'testaments' => $contents,
            'chapter_set' => $chapter_set,
            'latest_chapter' => $latest_chapter, 
            'earliest_chapter' => $earliest_chapter,
            'previous_volume_latest_chapter' => $previous_volume_latest_chapter]);
    }
}
