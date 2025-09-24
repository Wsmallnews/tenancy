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
        return $authUser->can('ViewAny:AwardTypes:AwardType');
    }

    public function view(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('View:AwardTypes:AwardType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AwardTypes:AwardType');
    }

    public function update(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Update:AwardTypes:AwardType');
    }

    public function delete(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Delete:AwardTypes:AwardType');
    }

    public function restore(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Restore:AwardTypes:AwardType');
    }

    public function forceDelete(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('ForceDelete:AwardTypes:AwardType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AwardTypes:AwardType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AwardTypes:AwardType');
    }

    public function replicate(AuthUser $authUser, AwardType $awardType): bool
    {
        return $authUser->can('Replicate:AwardTypes:AwardType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AwardTypes:AwardType');
    }

}