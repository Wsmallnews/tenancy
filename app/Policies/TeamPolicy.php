<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Teams:Team');
    }

    public function view(AuthUser $authUser, Team $team): bool
    {
        return $authUser->can('View:Teams:Team');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Teams:Team');
    }

    public function update(AuthUser $authUser, Team $team): bool
    {
        return $authUser->can('Update:Teams:Team');
    }

    public function delete(AuthUser $authUser, Team $team): bool
    {
        return $authUser->can('Delete:Teams:Team');
    }

    public function restore(AuthUser $authUser, Team $team): bool
    {
        return $authUser->can('Restore:Teams:Team');
    }

    public function forceDelete(AuthUser $authUser, Team $team): bool
    {
        return $authUser->can('ForceDelete:Teams:Team');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Teams:Team');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Teams:Team');
    }

    public function replicate(AuthUser $authUser, Team $team): bool
    {
        return $authUser->can('Replicate:Teams:Team');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Teams:Team');
    }
}
