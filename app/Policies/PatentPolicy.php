<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Patent;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Patent');
    }

    public function view(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('View:Patent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Patent');
    }

    public function update(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Update:Patent');
    }

    public function delete(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Delete:Patent');
    }

    public function restore(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Restore:Patent');
    }

    public function forceDelete(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('ForceDelete:Patent');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Patent');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Patent');
    }

    public function replicate(AuthUser $authUser, Patent $patent): bool
    {
        return $authUser->can('Replicate:Patent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Patent');
    }

}