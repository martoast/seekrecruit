<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role->value !== $role) {
            return redirect($user->isAdmin() ? route('admin.dashboard') : route('candidate.dashboard'));
        }

        return $next($request);
    }
}
