<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Client;
use App\Models\User;

/**
 * Client-scoping helpers for admin controllers.
 *
 * HR admins are locked to their own client_id. Super admins see everything
 * unless they pass ?client_id=X — in which case we scope to that one client
 * just for the request. The filter is not session-persisted; it lives on the URL.
 */
trait ScopesToClient
{
    /**
     * The client_id to filter by, or null for "show everything" (Super Admin, no filter).
     */
    protected function activeClientId(User $user, ?int $requestedClientId = null): ?int
    {
        if ($user->isHrAdmin()) {
            return $user->client_id;
        }

        // Super admin: honor the URL filter if present
        return $requestedClientId ?: null;
    }

    /**
     * True if this user may touch records belonging to $clientId.
     */
    protected function userOwnsClient(User $user, ?int $clientId): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isHrAdmin()) {
            return $user->client_id === $clientId;
        }

        return false;
    }

    /**
     * Load the Client currently being viewed (Super Admin's filter picks one,
     * HR Admin always gets their own). Null when Super Admin has no filter.
     */
    protected function activeClient(User $user, ?int $requestedClientId = null): ?Client
    {
        $id = $this->activeClientId($user, $requestedClientId);

        return $id ? Client::find($id) : null;
    }
}
