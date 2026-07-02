<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AccurateIdentify;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AccurateIdentifyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AccurateIdentifies:AccurateIdentify');
    }

    public function view(AuthUser $authUser, AccurateIdentify $accurateIdentify): bool
    {
        return $authUser->can('View:AccurateIdentifies:AccurateIdentify');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AccurateIdentifies:AccurateIdentify');
    }

    public function update(AuthUser $authUser, AccurateIdentify $accurateIdentify): bool
    {
        return $authUser->can('Update:AccurateIdentifies:AccurateIdentify');
    }

    public function delete(AuthUser $authUser, AccurateIdentify $accurateIdentify): bool
    {
        return $authUser->can('Delete:AccurateIdentifies:AccurateIdentify');
    }

    public function restore(AuthUser $authUser, AccurateIdentify $accurateIdentify): bool
    {
        return $authUser->can('Restore:AccurateIdentifies:AccurateIdentify');
    }

    public function forceDelete(AuthUser $authUser, AccurateIdentify $accurateIdentify): bool
    {
        return $authUser->can('ForceDelete:AccurateIdentifies:AccurateIdentify');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AccurateIdentifies:AccurateIdentify');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AccurateIdentifies:AccurateIdentify');
    }

    public function replicate(AuthUser $authUser, AccurateIdentify $accurateIdentify): bool
    {
        return $authUser->can('Replicate:AccurateIdentifies:AccurateIdentify');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AccurateIdentifies:AccurateIdentify');
    }
}
