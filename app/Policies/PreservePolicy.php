<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Preserve;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreservePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Preserve');
    }

    public function view(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('View:Preserve');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Preserve');
    }

    public function update(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Update:Preserve');
    }

    public function delete(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Delete:Preserve');
    }

    public function restore(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Restore:Preserve');
    }

    public function forceDelete(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('ForceDelete:Preserve');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Preserve');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Preserve');
    }

    public function replicate(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Replicate:Preserve');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Preserve');
    }

}