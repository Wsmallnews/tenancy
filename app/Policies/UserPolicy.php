<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Admins:User');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:Admins:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Admins:User');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:Admins:User');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:Admins:User');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('Restore:Admins:User');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDelete:Admins:User');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Admins:User');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Admins:User');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('Replicate:Admins:User');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Admins:User');
    }
}
