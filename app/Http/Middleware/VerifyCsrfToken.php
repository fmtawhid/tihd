<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/payment/success',
        '/cancel-subscription', // maybe not need this one
        'payment/success',
        'payment/fail',
        'payment/cancel',
        'payment/ipn',   // 🔥 নতুন যোগ
    ];
}
