<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_certificates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('view_certificates') &&
               ($certificate->user_id === $user->id || $user->hasRole('admin'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_certificates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('edit_certificates') &&
               ($certificate->user_id === $user->id || $user->hasRole('admin'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('delete_certificates') &&
               ($certificate->user_id === $user->id || $user->hasRole('admin'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('restore_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('force_delete_certificates') && $user->hasRole('admin');
    }

    /**
     * Determine whether the user can renew the certificate.
     */
    public function renew(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('renew_certificates') &&
               ($certificate->user_id === $user->id || $user->hasRole('admin'));
    }

    /**
     * Determine whether the user can export the certificate.
     */
    public function export(User $user, Certificate $certificate): bool
    {
        return $user->hasPermission('export_certificates') &&
               ($certificate->user_id === $user->id || $user->hasRole('admin'));
    }
}
