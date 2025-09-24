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
        return $authUser->can('ViewAny:Appraises:Appraise');
    }

    public function view(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('View:Appraises:Appraise');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Appraises:Appraise');
    }

    public function update(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Update:Appraises:Appraise');
    }

    public function delete(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Delete:Appraises:Appraise');
    }

    public function restore(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Restore:Appraises:Appraise');
    }

    public function forceDelete(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('ForceDelete:Appraises:Appraise');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Appraises:Appraise');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Appraises:Appraise');
    }

    public function replicate(AuthUser $authUser, Appraise $appraise): bool
    {
        return $authUser->can('Replicate:Appraises:Appraise');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Appraises:Appraise');
    }

}