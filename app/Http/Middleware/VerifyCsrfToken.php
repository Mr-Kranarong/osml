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
        '/statistic/profit',
        '/statistic/views',
        '/statistic/wishlists',
        '/statistic/sales',
        '/statistic/categories',
        '/statistic/stocks'
    ];

}
