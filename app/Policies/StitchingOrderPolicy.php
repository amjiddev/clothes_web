<?php

namespace App\Policies;

use App\Models\StitchingOrder;
use App\Models\User;

class StitchingOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_stitching_orders');
    }

    public function view(User $user, StitchingOrder $stitchingOrder): bool
    {
        if ($user->can('view_stitching_orders')) {
            return true;
        }

        if ($user->isTailor() && $stitchingOrder->tailor_id === $user->id) {
            return $user->can('view_assigned_stitching_orders');
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create_orders');
    }

    public function update(User $user, StitchingOrder $stitchingOrder): bool
    {
        return $user->can('edit_orders');
    }

    public function delete(User $user, StitchingOrder $stitchingOrder): bool
    {
        return $user->can('delete_orders');
    }

    public function assignTailor(User $user, StitchingOrder $stitchingOrder): bool
    {
        return $user->can('assign_stitching_orders');
    }

    public function updateStatus(User $user, StitchingOrder $stitchingOrder): bool
    {
        if ($user->can('update_stitching_status')) {
            if ($user->isTailor()) {
                return $stitchingOrder->tailor_id === $user->id;
            }
            return true;
        }

        return false;
    }
}
