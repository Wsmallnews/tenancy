<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Assemble;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AssemblePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Assembles:Assemble');
    }

    public function view(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('View:Assembles:Assemble');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Assembles:Assemble');
    }

    public function update(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Update:Assembles:Assemble');
    }

    public function delete(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Delete:Assembles:Assemble');
    }

    public function restore(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Restore:Assembles:Assemble');
    }

    public function forceDelete(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('ForceDelete:Assembles:Assemble');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Assembles:Assemble');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Assembles:Assemble');
    }

    public function replicate(AuthUser $authUser, Assemble $assemble): bool
    {
        return $authUser->can('Replicate:Assembles:Assemble');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Assembles:Assemble');
    }
}
