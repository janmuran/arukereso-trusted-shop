<?php

declare(strict_types=1);

namespace Janmuran\Config;

final class Constant
{
    public const VERSION = '2.0/PHP';

    public const SERVICE_URL_SEND = 'https://www.arukereso.hu/';
    public const SERVICE_URL_AKU = 'https://assets.arukereso.com/aku.min.js';
    public const SERVICE_TOKEN_REQUEST = 't2/TokenRequest.php';
    public const SERVICE_TOKEN_PROCESS = 't2/TrustedShop.php';

    public const ERROR_EMPTY_EMAIL = "Customer e-mail address is empty.";
    public const ERROR_EMPTY_WEBAPIKEY = "Partner WebApiKey is empty.";
    public const ERROR_EXAMPLE_EMAIL = "Customer e-mail address has been not changed yet.";
    public const ERROR_EXAMPLE_PRODUCT = "Product name has been not changed yet.";
    public const ERROR_TOKEN_REQUEST_TIMED_OUT = "Token request timed out.";
    public const ERROR_TOKEN_REQUEST_FAILED = "Token request failed.";
    public const ERROR_TOKEN_BAD_REQUEST = "Bad request: ";
}
