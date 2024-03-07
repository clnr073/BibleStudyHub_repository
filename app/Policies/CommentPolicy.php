<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Models\Note;
use App\Models\Connection;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Comment $comment): Response
    {
        // ユーザーがノートを編集・更新できるかどうか確認
        return $user->id === $comment->user_id
                    ? Response::allow()
                    : Response::deny('申し訳ありませんが、このコメントを編集または更新する権限がありません。');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Comment $comment): Response
    {
        // ユーザーがノートを削除できるかどうか確認
        return $user->id === $comment->user_id
                    ? Response::allow()
                    : Response::deny('申し訳ありませんが、このコメントを削除する権限がありません。');
    }
}
