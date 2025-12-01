<?php

function hr_can($key)
{
    $user = Auth::user();

    if (!$user) return false;

    // If user is HR and has NO record yet => allow everything
    if ($user->is_role == 1) {
        $perm = \App\Models\HrPermission::where('user_id', $user->id)
                   ->where('company_id', session('company_id'))
                   ->first();

        // Old HR without permissions record → full access
        if (!$perm) return true;

        return in_array($key, $perm->permissions ?? []);
    }

    return false;
}

