<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultCategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->user_type == "A";
    }

    public function update(User $user)
    {
        return $user->user_type == "A";
    }

    public function delete(User $user)
    {
        return $user->user_type == "A";
    }
}
