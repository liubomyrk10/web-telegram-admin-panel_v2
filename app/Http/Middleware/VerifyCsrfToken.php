<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/webhook',
        '/{token}/webhook'
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        if ($request->route() && in_array($request->route()->getName(), ['webhook.receive'])) {
            return true;
        }

        return parent::inExceptArray($request);
    }
}
