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
    public function showTestaments(Testament $testament) // 依存性の注入:引数のタイプヒントにTestamentを指定
    { 
        return view('testaments.show')->with(['testaments' => $testament->get()]);
    }
}
