<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(Tag $tag)
    {
        $user_id = Auth::id();
        
        return view('tags.index')->with(['tags' => $tag->getPaginateByLimit($user_id), 'user_id' => $user_id]);
    }
    
    public function store(TagRequest $request, Tag $tag)
    {
        $input_tag = $request['tag'];
        $input_tag += ['user_id' => $request->user()->id];
        
        $tag->fill($input_tag)->save();
        
        return redirect(route('tags.index'));
    }
    
    public function edit(Tag $tag)
    {
        return view('tags.edit')->with(['tag' => $tag]);
    }
    
    public function update(TagRequest $request, Tag $tag)
    {
        $input_tag = $request['tag'];
        $input_tag += ['user_id' => $request->user()->id];
        
        $tag->fill($input_tag)->save();
        
        return redirect(route('tags.index'));
    }
    
    public function delete(Tag $tag)
    {
        $tag->delete();
        return redirect(route('tags.index'));
    }
}
