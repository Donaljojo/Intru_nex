<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // This is required for GitHub Codespaces and similar environments
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        Request::setTrustedProxies([$_SERVER['REMOTE_ADDR']], Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
    }

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};