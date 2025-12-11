<?php

function hr_can($key)
{
    $user = Auth::user();
    if (!$user) return false;

    if ($user->is_role == 1) {
        // Cache per user ID
        static $cache = [];

        $cacheKey = $user->id . '_' . session('company_id');

        if (!isset($cache[$cacheKey])) {
            $perm = \App\Models\HrPermission::where('user_id', $user->id)
                       ->where('company_id', session('company_id'))
                       ->first();

            if (!$perm) {
                $cache[$cacheKey] = true; // full access
            } else {
                $cache[$cacheKey] = is_string($perm->permissions)
                    ? json_decode($perm->permissions, true)
                    : $perm->permissions;
            }
        }

        if ($cache[$cacheKey] === true) return true;

        return in_array($key, $cache[$cacheKey] ?? []);
    }

    return false;
}
