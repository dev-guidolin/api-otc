<?php

namespace App\Http\Middleware;

use App\Enum\StatusEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->status !== StatusEnum::Active->value) {
            $message = match ($user->status) {
                StatusEnum::Pending->value => 'Your account is pending verification.',
                StatusEnum::OnHold->value => 'Your account is currently under review.',
                StatusEnum::Rejected->value => 'Your account has been rejected.',
                default => 'Access denied.',
            };

            abort(401, $message);
        }

        return $next($request);
    }
}
