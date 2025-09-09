<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Catalog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatalogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Catalog');
    }

    public function view(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('View:Catalog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Catalog');
    }

    public function update(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Update:Catalog');
    }

    public function delete(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Delete:Catalog');
    }

    public function restore(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Restore:Catalog');
    }

    public function forceDelete(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('ForceDelete:Catalog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Catalog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Catalog');
    }

    public function replicate(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Replicate:Catalog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Catalog');
    }

}