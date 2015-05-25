<?php

namespace WebServer\Protocol\Http;

class ResponseCode {
    // 1xx
    const STATUS_CONTINUE       = 100;
    const STATUS_SWITCHING_PROTOCOLS = 101;

    // 2xx
    const STATUS_OK     = 200;

    // 3xx

    // 4xx
    const STATUS_BAD_REQUEST    = 400;
    const STATUS_NOT_FOUND      = 404;


    // 5xx
    const STATUS_INTERNAL_SERVER_ERROR    = 500;
    const STATUS_NOT_IMPLEMENTED          = 501;
    const STATUS_BAD_GATEWAY              = 502;
    const STATUS_SERVICE_UNAVAILABLE      = 503;
    const STATUS_GATEWAY_TIMEOUT          = 504;
    const STATUS_VERSION_NOT_SUPPORTED    = 505;


    static protected $status_texts = array(
        // 1xx
        self::STATUS_CONTINUE               => "Continue",
        self::STATUS_SWITCHING_PROTOCOLS    => "Switching Protocols",

        // 2xx
        self::STATUS_OK     => "Ok",

        // 3xx

        // 4xx
        self::STATUS_BAD_REQUEST    => "Bad Request",
        self::STATUS_NOT_FOUND      => "Not Found",

        // 5xx
        self::STATUS_INTERNAL_SERVER_ERROR    => "Internal Server Error",
        self::STATUS_NOT_IMPLEMENTED          => "Not Implemented",
        self::STATUS_BAD_GATEWAY              => "Bad Gateway",
        self::STATUS_SERVICE_UNAVAILABLE      => "Service Unavailable",
        self::STATUS_GATEWAY_TIMEOUT          => "Gateway Timeout",
        self::STATUS_VERSION_NOT_SUPPORTED    => "HTTP Version Not Supported",
    );


    static function getStatusMessage($status_code) {
        if (! array_key_exists($status_code, self::$status_texts)) {
            return "Unknown Status";
        }

        return self::$status_texts[$status_code];
    }

}
