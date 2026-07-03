<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, 'غير مصرح لك بالوصول إلى لوحة التحكم.');
        }

        return $next($request);
    }
}
