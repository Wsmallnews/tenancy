<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PatentType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PatentTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PatentTypes:PatentType');
    }

    public function view(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('View:PatentTypes:PatentType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PatentTypes:PatentType');
    }

    public function update(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Update:PatentTypes:PatentType');
    }

    public function delete(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Delete:PatentTypes:PatentType');
    }

    public function restore(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Restore:PatentTypes:PatentType');
    }

    public function forceDelete(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('ForceDelete:PatentTypes:PatentType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PatentTypes:PatentType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PatentTypes:PatentType');
    }

    public function replicate(AuthUser $authUser, PatentType $patentType): bool
    {
        return $authUser->can('Replicate:PatentTypes:PatentType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PatentTypes:PatentType');
    }
}
