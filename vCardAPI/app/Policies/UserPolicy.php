<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    public function view(User $user, User $model)
    {
        return $user->user_type == "A" || $user->id == $model->id;
    }
    public function update(User $user, User $model)
    {
        return $user->user_type == "A" || $user->id == $model->id;
    }
    public function updateAdmin(User $user)
    {
        return $user->user_type == "A";
    }
    public function updatePassword(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    public function delete(User $user)
    {
        return $user->user_type == "A";
    }
}
