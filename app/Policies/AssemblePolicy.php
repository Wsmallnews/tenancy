<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Assemble;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssemblePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Assemble');
    }

    public function view(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('View:Assemble');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Assemble');
    }

    public function update(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Update:Assemble');
    }

    public function delete(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Delete:Assemble');
    }

    public function restore(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Restore:Assemble');
    }

    public function forceDelete(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('ForceDelete:Assemble');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Assemble');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Assemble');
    }

    public function replicate(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Replicate:Assemble');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Assemble');
    }

}