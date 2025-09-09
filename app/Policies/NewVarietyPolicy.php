<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NewVariety;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewVarietyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NewVariety');
    }

    public function view(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('View:NewVariety');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NewVariety');
    }

    public function update(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Update:NewVariety');
    }

    public function delete(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Delete:NewVariety');
    }

    public function restore(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Restore:NewVariety');
    }

    public function forceDelete(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('ForceDelete:NewVariety');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NewVariety');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NewVariety');
    }

    public function replicate(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Replicate:NewVariety');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NewVariety');
    }

}