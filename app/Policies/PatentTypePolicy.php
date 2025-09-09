<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PatentType;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatentTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PatentType');
    }

    public function view(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('View:PatentType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PatentType');
    }

    public function update(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Update:PatentType');
    }

    public function delete(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Delete:PatentType');
    }

    public function restore(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Restore:PatentType');
    }

    public function forceDelete(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('ForceDelete:PatentType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PatentType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PatentType');
    }

    public function replicate(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Replicate:PatentType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PatentType');
    }

}