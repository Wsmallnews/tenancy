<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PhenotypeIdentify;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PhenotypeIdentifyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function view(AuthUser $authUser, PhenotypeIdentify $phenotypeIdentify): bool
    {
        return $authUser->can('View:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function update(AuthUser $authUser, PhenotypeIdentify $phenotypeIdentify): bool
    {
        return $authUser->can('Update:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function delete(AuthUser $authUser, PhenotypeIdentify $phenotypeIdentify): bool
    {
        return $authUser->can('Delete:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function restore(AuthUser $authUser, PhenotypeIdentify $phenotypeIdentify): bool
    {
        return $authUser->can('Restore:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function forceDelete(AuthUser $authUser, PhenotypeIdentify $phenotypeIdentify): bool
    {
        return $authUser->can('ForceDelete:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function replicate(AuthUser $authUser, PhenotypeIdentify $phenotypeIdentify): bool
    {
        return $authUser->can('Replicate:PhenotypeIdentifies:PhenotypeIdentify');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PhenotypeIdentifies:PhenotypeIdentify');
    }
}
