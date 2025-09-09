<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Appraise;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppraisePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Appraise');
    }

    public function view(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('View:Appraise');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Appraise');
    }

    public function update(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Update:Appraise');
    }

    public function delete(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Delete:Appraise');
    }

    public function restore(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Restore:Appraise');
    }

    public function forceDelete(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('ForceDelete:Appraise');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Appraise');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Appraise');
    }

    public function replicate(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Replicate:Appraise');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Appraise');
    }

}