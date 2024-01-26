<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connection;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function index(Connection $connection)
    {
        $user_id = Auth::id();
        $friends = $connection::where(function ($query) use ($user_id) {
            $query->orWhere('follow_id', $user_id)
                  ->orWhere('followed_id', $user_id)
                  ->where('approval', 1);
        })->get();
        
        $followers = $connection::where(function ($query) use ($user_id) {
            $query->where('followed_id', $user_id)
                  ->where('approval', 0);
        })->get();
        
        return view('connections.index')->with([
            'friends' => $friends,
            'followers' => $followers,
            ]);
    }
    
    public function approvalUserRequest(Connection $connection, Request $request)
    {
        $input_id = $request->input('connections_id');
        $target_connection = $connection->find($input_id);
        
        $target_connection->update(['approval' => 1]);
        
        return redirect('/connections');
    }
    
    public function unFriend(Connection $connection, Request $request)
    {
        $input_id = $request->input('connections_id');
        $target_connection = $connection->find($input_id);
        
        $target_connection->update(['approval' => 0]);
        
        return redirect('/connections');
    }
}
