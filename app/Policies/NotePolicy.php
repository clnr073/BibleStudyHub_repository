<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use App\Models\Connection;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Note $note): Response
    {
        $user_id = $user->id;
        
        // ユーザーがNoteを閲覧できるどうかを確認
        if ($user_id === $note->user_id) {
            return Response::allow();
        }
        
        // 友達のidを取得
        $friend_ids = Connection::where(function ($query) use ($user_id) {
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
        
        // $noteが友人のもので、かつ公開ノートであるかどうか確認
        if (in_array($note->user_id, $friend_ids)) {
            if ($note->public === 1) {
                return Response::allow();
            }
        }
        
        return Response::deny('申し訳ありませんが、このノート及びコメントを閲覧する権限がありません。');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Note $note): Response
    {
        // ユーザーがノートを編集・更新できるかどうか確認
        return $user->id === $note->user_id
                    ? Response::allow()
                    : Response::deny('申し訳ありませんが、このノートを編集または更新する権限がありません。');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Note $note): Response
    {
        // ユーザーがノートを削除できるかどうか確認
        return $user->id === $note->user_id
                    ? Response::allow()
                    : Response::deny('申し訳ありませんが、このノートを削除する権限がありません。');
    }
}
