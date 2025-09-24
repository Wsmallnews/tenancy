<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ThesisType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThesisTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ThesisTypes:ThesisType');
    }

    public function view(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('View:ThesisTypes:ThesisType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThesisTypes:ThesisType');
    }

    public function update(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Update:ThesisTypes:ThesisType');
    }

    public function delete(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Delete:ThesisTypes:ThesisType');
    }

    public function restore(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Restore:ThesisTypes:ThesisType');
    }

    public function forceDelete(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('ForceDelete:ThesisTypes:ThesisType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThesisTypes:ThesisType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThesisTypes:ThesisType');
    }

    public function replicate(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Replicate:ThesisTypes:ThesisType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThesisTypes:ThesisType');
    }

}