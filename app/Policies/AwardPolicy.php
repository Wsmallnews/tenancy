<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Award;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AwardPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Awards:Award');
    }

    public function view(AuthUser $authUser, Award $award): bool
    {
        return $authUser->can('View:Awards:Award');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Awards:Award');
    }

    public function update(AuthUser $authUser, Award $award): bool
    {
        return $authUser->can('Update:Awards:Award');
    }

    public function delete(AuthUser $authUser, Award $award): bool
    {
        return $authUser->can('Delete:Awards:Award');
    }

    public function restore(AuthUser $authUser, Award $award): bool
    {
        return $authUser->can('Restore:Awards:Award');
    }

    public function forceDelete(AuthUser $authUser, Award $award): bool
    {
        return $authUser->can('ForceDelete:Awards:Award');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Awards:Award');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Awards:Award');
    }

    public function replicate(AuthUser $authUser, Award $award): bool
    {
        return $authUser->can('Replicate:Awards:Award');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Awards:Award');
    }
}
