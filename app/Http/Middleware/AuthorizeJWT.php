<?php

namespace App\Http\Middleware;

use App\Custom\Body;
use App\Traits\TokenKey;
use App\User;
use Closure;

class AuthorizeJWT
{
    use TokenKey;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!($token = $request->header('Authorization'))) {
            return response()->json(Body::set()->message('there is no token'), 403);
        }

        if (! User::keyExists($token)) {
            return response()->json(Body::set()->message('you are not authorized'), 403);
        }
        $request->attributes->add(['token_key' => $this->tokenKey($token)]);

        return $next($request);
    }
}
