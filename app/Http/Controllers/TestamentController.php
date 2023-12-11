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
     public function index(Volume $volume)
     {
         return view('testaments.index')->with(['volumes' => $volume->get()]);
     }
     
    /**
     * volumeの章一覧を表示
     */
    public function displayVolumeWithContents($volume ,Testament $testament)
    {
        $chapters = $testament->where('volume_id', $volume)->groupBy('chapter')->pluck('chapter');
        
        return view('testaments.volume')->with(['volume' => $volume, 'chapters' => $chapters]);
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
        
        // 最小のchapterを取得
        $earliest_chapter = $testament->getChapter($volume, false);
        
        // 最大のchapterを取得
        $latest_chapter = $testament->getChapter($volume);
        
        //インスタンスのvolume_id未満のvolume_idの中で最新のchapterを取得                         
        $previous_volume_latest_chapter = $testament->getPreviousVolumeLatestChapter($volume);
        
        return view('testaments.show')->with([
            'volume' => $volume,
            'chapter' => $chapter,
            'testaments' => $contents,
            'chapter_set' => $chapter_set,
            'latest_chapter' => $latest_chapter, 
            'earliest_chapter' => $earliest_chapter,
            'previous_volume_latest_chapter' => $previous_volume_latest_chapter]);
    }
}
