<?php


namespace app\common\lib;
use think\exception\HttpException;

class HttpExceptions extends HttpException
{
    protected $error_type;
    protected $default_code = 500;
    private $headers;
    private $error_code;

    public function __construct($message = null, $error_type,\Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->error_code = $this->getHttpCode($error_type);
        parent:: __construct($this->error_code, $message, $previous,$headers , $code = 0);
    }

    public function getHttpCode($error_type)
    {
        static $http_codes = [
            'BadRequest'                    => 400,
            'Unauthorized'                  => 401,
            'PaymentRequired'               => 402,
            'Forbidden'                     => 403,
            'NotFound'                      => 404,
            'MethodNotAllowed'              => 405,
            'NotAcceptable'                 => 406,
            'ProxyAuthenticationRequired'   => 407,
            'RequestTimeout'                => 408,
            'Conflict'                      => 409,
            'Gone'                          => 410,
            'LengthRequired'                => 411,
            'PreconditionFailed'            => 412,
            'PayloadTooLarge'               => 413,
            'URITooLong'                    => 414,
            'UnsupportedMediaType'          => 415,
            'RangeNotSatisfiable'           => 416,
            'ExpectationFailed'             => 417,
            'ImATeapot'                     => 418,
            'MisdirectedRequest'            => 421,
            'UnprocessableEntity'           => 422,
            'Locked'                        => 423,
            'FailedDependency'              => 424,
            'UnorderedCollection'           => 425,
            'UpgradeRequired'               => 426,
            'PreconditionRequired'          => 428,
            'TooManyRequests'               => 429,
            'RequestHeaderFieldsTooLarge'   => 431,
            'UnavailableForLegalReasons'    => 451,
            'SystemError'                   => 500,
            'InternalServerError'           => 500,
            'NotImplemented'                => 501,
            'BadGateway'                    => 502,
            'ServiceUnavailable'            => 503,
            'GatewayTimeout'                => 504,
            'HTTPVersionNotSupported'       => 505,
            'VariantAlsoNegotiates'         => 506,
            'InsufficientStorage'           => 507,
            'LoopDetected'                  => 508,
            'BandwidthLimitExceeded'        => 509,
            'NotExtended'                   => 510,
            'NetworkAuthenticationRequired' => 511,
            'PermissionDenied'              => 60001
        ];

        if(isset($http_codes[$error_type])) {
            return $http_codes[$error_type];
        }
        return $this->default_code;
    }
}
