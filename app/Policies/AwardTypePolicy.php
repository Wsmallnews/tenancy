<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AwardType;
use Illuminate\Auth\Access\HandlesAuthorization;

class AwardTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AwardType');
    }

    public function view(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('View:AwardType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AwardType');
    }

    public function update(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Update:AwardType');
    }

    public function delete(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Delete:AwardType');
    }

    public function restore(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Restore:AwardType');
    }

    public function forceDelete(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('ForceDelete:AwardType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AwardType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AwardType');
    }

    public function replicate(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Replicate:AwardType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AwardType');
    }

}