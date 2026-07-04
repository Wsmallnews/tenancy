<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\NewVariety;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class NewVarietyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NewVarieties:NewVariety');
    }

    public function view(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('View:NewVarieties:NewVariety');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NewVarieties:NewVariety');
    }

    public function update(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Update:NewVarieties:NewVariety');
    }

    public function delete(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Delete:NewVarieties:NewVariety');
    }

    public function restore(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Restore:NewVarieties:NewVariety');
    }

    public function forceDelete(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('ForceDelete:NewVarieties:NewVariety');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NewVarieties:NewVariety');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NewVarieties:NewVariety');
    }

    public function replicate(AuthUser $authUser, NewVariety $newVariety): bool
    {
        return $authUser->can('Replicate:NewVarieties:NewVariety');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NewVarieties:NewVariety');
    }
}
