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
        return $authUser->can('ViewAny:Catalogs:Catalog');
    }

    public function view(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('View:Catalogs:Catalog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Catalogs:Catalog');
    }

    public function update(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Update:Catalogs:Catalog');
    }

    public function delete(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Delete:Catalogs:Catalog');
    }

    public function restore(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Restore:Catalogs:Catalog');
    }

    public function forceDelete(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('ForceDelete:Catalogs:Catalog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Catalogs:Catalog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Catalogs:Catalog');
    }

    public function replicate(AuthUser $authUser, Catalog $catalog): bool
    {
        return $authUser->can('Replicate:Catalogs:Catalog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Catalogs:Catalog');
    }

}