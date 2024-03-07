<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Tag $tag): Response
    {
        // ユーザーがタグを編集・更新できるかどうか確認
        return $user->id === $tag->user_id
                    ? Response::allow()
                    : Response::deny('申し訳ありませんが、このタグを編集または更新する権限がありません。');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Tag $tag): Response
    {
        // ユーザーがタグを編集・更新できるかどうか確認
        return $user->id === $tag->user_id
                    ? Response::allow()
                    : Response::deny('申し訳ありませんが、このタグを削除する権限がありません。');
    }
}
