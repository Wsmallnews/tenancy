<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProjectManage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectManagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProjectManages:ProjectManage');
    }

    public function view(AuthUser $authUser, ProjectManage $projectManage): bool
    {
        return $authUser->can('View:ProjectManages:ProjectManage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProjectManages:ProjectManage');
    }

    public function update(AuthUser $authUser, ProjectManage $projectManage): bool
    {
        return $authUser->can('Update:ProjectManages:ProjectManage');
    }

    public function delete(AuthUser $authUser, ProjectManage $projectManage): bool
    {
        return $authUser->can('Delete:ProjectManages:ProjectManage');
    }

    public function restore(AuthUser $authUser, ProjectManage $projectManage): bool
    {
        return $authUser->can('Restore:ProjectManages:ProjectManage');
    }

    public function forceDelete(AuthUser $authUser, ProjectManage $projectManage): bool
    {
        return $authUser->can('ForceDelete:ProjectManages:ProjectManage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProjectManages:ProjectManage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProjectManages:ProjectManage');
    }

    public function replicate(AuthUser $authUser, ProjectManage $projectManage): bool
    {
        return $authUser->can('Replicate:ProjectManages:ProjectManage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProjectManages:ProjectManage');
    }

}