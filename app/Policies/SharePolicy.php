<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Share;
use Illuminate\Auth\Access\HandlesAuthorization;

class SharePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Shares:Share');
    }

    public function view(AuthUser $authUser, Share $share): bool
    {
        return $authUser->can('View:Shares:Share');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Shares:Share');
    }

    public function update(AuthUser $authUser, Share $share): bool
    {
        return $authUser->can('Update:Shares:Share');
    }

    public function delete(AuthUser $authUser, Share $share): bool
    {
        return $authUser->can('Delete:Shares:Share');
    }

    public function restore(AuthUser $authUser, Share $share): bool
    {
        return $authUser->can('Restore:Shares:Share');
    }

    public function forceDelete(AuthUser $authUser, Share $share): bool
    {
        return $authUser->can('ForceDelete:Shares:Share');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Shares:Share');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Shares:Share');
    }

    public function replicate(AuthUser $authUser, Share $share): bool
    {
        return $authUser->can('Replicate:Shares:Share');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Shares:Share');
    }

}