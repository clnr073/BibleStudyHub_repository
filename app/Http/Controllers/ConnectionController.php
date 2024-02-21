<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function index(Connection $connection)
    {
        $user_id = Auth::id();
        
        // 友達のidを取得
        $friend_ids = $connection::where(function ($query) use ($user_id) {
            $query->where('approval', 1)
                  ->where(function ($query) use ($user_id) {
                      $query->orWhere('follow_id', $user_id)
                            ->orWhere('followed_id', $user_id);
                  });
        })->get()->flatMap(function ($record) use ($user_id) {
            return [$record->follow_id, $record->followed_id];
        })->reject(function ($friend_id) use ($user_id) {
            return $friend_id == $user_id;
        })->unique()->values()->toArray();

        
        // 友達でないユーザーのレコードを取得
        $users = User::whereNotIn('id', $friend_ids)
             ->where('id', '!=', $user_id)
             ->paginate(10);

        // ユーザーの友達のレコードを取得
        $friends = User::whereIn('id', $friend_ids)->paginate(10);
        
        $followers = $connection::where(function ($query) use ($user_id) {
            $query->where('followed_id', $user_id)
                  ->where('approval', 0);
        })->paginate(10);
        
        return view('connections.index')->with([
            'users' => $users,
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
        $input_id = $request->input('friend_id');
        $user_id = Auth::id();
        
        // 対象のconnectionを取得
        $target_connection = $connection::where(function ($query) use ($input_id, $user_id) {
            $query->where('approval', 1)
                  ->where(function ($query) use ($input_id, $user_id) {
                      $query->orwhere('follow_id', $input_id)
                            ->orWhere('followed_id', $input_id);
                  })
                  ->where(function ($query) use ($input_id, $user_id) {
                      $query->orwhere('follow_id', $user_id)
                            ->orWhere('followed_id', $user_id);
                  });
        });
        
        $target_connection->update(['approval' => 0]);
        
        return redirect('/connections');
    }
    
    public function followUser(Connection $connection, Request $request)
    {
        $input_id = $request->input('user_id');
        
        $new_connection = ['follow_id' => $request->user()->id];
        $new_connection += ['followed_id' => $input_id];
        $new_connection += ['approval' => 0];
        
        $connection->fill($new_connection)->save();
        
        return redirect('/connections');
    }
}
