<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PatentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Patents:Patent');
    }

    public function view(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('View:Patents:Patent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Patents:Patent');
    }

    public function update(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Update:Patents:Patent');
    }

    public function delete(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Delete:Patents:Patent');
    }

    public function restore(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Restore:Patents:Patent');
    }

    public function forceDelete(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('ForceDelete:Patents:Patent');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Patents:Patent');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Patents:Patent');
    }

    public function replicate(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Replicate:Patents:Patent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Patents:Patent');
    }
}
