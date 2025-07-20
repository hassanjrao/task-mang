<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGroupInvitations
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $pendingInvite = \App\Models\GroupInvitation::with('group')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($pendingInvite) {
                session()->flash('pending_invite_group', $pendingInvite);
            }
        }

        return $next($request);

    }
}
