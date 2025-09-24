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
        return $authUser->can('ViewAny:Preserves:Preserve');
    }

    public function view(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('View:Preserves:Preserve');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Preserves:Preserve');
    }

    public function update(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Update:Preserves:Preserve');
    }

    public function delete(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Delete:Preserves:Preserve');
    }

    public function restore(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Restore:Preserves:Preserve');
    }

    public function forceDelete(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('ForceDelete:Preserves:Preserve');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Preserves:Preserve');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Preserves:Preserve');
    }

    public function replicate(AuthUser $authUser, Preserve $preserve): bool
    {
        return $authUser->can('Replicate:Preserves:Preserve');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Preserves:Preserve');
    }

}