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
        return $authUser->can('ViewAny:ThesisType');
    }

    public function view(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('View:ThesisType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThesisType');
    }

    public function update(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Update:ThesisType');
    }

    public function delete(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Delete:ThesisType');
    }

    public function restore(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Restore:ThesisType');
    }

    public function forceDelete(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('ForceDelete:ThesisType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThesisType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThesisType');
    }

    public function replicate(AuthUser $authUser, ThesisType $thesisType): bool
    {
        return $authUser->can('Replicate:ThesisType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThesisType');
    }

}