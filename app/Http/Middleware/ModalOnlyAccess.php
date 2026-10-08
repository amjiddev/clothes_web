<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModalOnlyAccess
{
    /**
     * Handle an incoming request.
     * Only allow access if loaded in an iframe (modal)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request is from an iframe
        $secFetchDest = $request->header('Sec-Fetch-Dest');
        
        // If not iframe, block access
        if ($secFetchDest !== 'iframe') {
            abort(404, 'This page is only accessible through the modal. Please use the login/register links on the homepage.');
        }

        return $next($request);
    }
}
