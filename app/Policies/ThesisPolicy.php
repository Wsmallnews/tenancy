<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Thesis;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThesisPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Theses:Thesis');
    }

    public function view(AuthUser $authUser, Thesis $thesis): bool
    {
        return $authUser->can('View:Theses:Thesis');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Theses:Thesis');
    }

    public function update(AuthUser $authUser, Thesis $thesis): bool
    {
        return $authUser->can('Update:Theses:Thesis');
    }

    public function delete(AuthUser $authUser, Thesis $thesis): bool
    {
        return $authUser->can('Delete:Theses:Thesis');
    }

    public function restore(AuthUser $authUser, Thesis $thesis): bool
    {
        return $authUser->can('Restore:Theses:Thesis');
    }

    public function forceDelete(AuthUser $authUser, Thesis $thesis): bool
    {
        return $authUser->can('ForceDelete:Theses:Thesis');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Theses:Thesis');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Theses:Thesis');
    }

    public function replicate(AuthUser $authUser, Thesis $thesis): bool
    {
        return $authUser->can('Replicate:Theses:Thesis');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Theses:Thesis');
    }

}