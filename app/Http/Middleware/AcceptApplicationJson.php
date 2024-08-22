<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcceptApplicationJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader != 'application/json') {
            return response()->json([
                "error" => "requests must have the Accept: application/json header"
            ], 406);
        }

        return $next($request);
    }
}
