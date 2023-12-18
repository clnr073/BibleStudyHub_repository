<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index(Tag $tag)
    {
        return view('tags.index')->with(['tags' => $tag->get()]);
    }
    
    public function store(Request $request, Tag $tag)
    {
        $input_tag = $request['tag'];
        $input_tag += ['user_id' => $request->user()->id];
        
        $tag->fill($input_tag)->save();
        
        return redirect(route('tags.index'));
    }
}
