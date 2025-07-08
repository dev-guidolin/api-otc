<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SystemHelper
{
    public function destroySessionAndRender(string $component): Response
    {
        if (Auth::check()) {
            Auth::logout();
            session()?->invalidate();
            session()?->regenerateToken();
        }

        return Inertia::render("Exceptions/$component");
    }
}
