<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Companies:Company');
    }

    public function view(AuthUser $authUser, Company $company): bool
    {
        return $authUser->can('View:Companies:Company');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Companies:Company');
    }

    public function update(AuthUser $authUser, Company $company): bool
    {
        return $authUser->can('Update:Companies:Company');
    }

    public function delete(AuthUser $authUser, Company $company): bool
    {
        return $authUser->can('Delete:Companies:Company');
    }

    public function restore(AuthUser $authUser, Company $company): bool
    {
        return $authUser->can('Restore:Companies:Company');
    }

    public function forceDelete(AuthUser $authUser, Company $company): bool
    {
        return $authUser->can('ForceDelete:Companies:Company');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Companies:Company');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Companies:Company');
    }

    public function replicate(AuthUser $authUser, Company $company): bool
    {
        return $authUser->can('Replicate:Companies:Company');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Companies:Company');
    }
}
