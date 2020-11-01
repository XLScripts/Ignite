<?php namespace Ignite\Components;

class OutgoingResponse {
    private $statusCode        = 200;
    private $statusText        = 'OK';
    private $responseBody      = null;
    private $responseHeaders   = [
        'Content-Type' => 'text/html'
    ]; 

    public static $statusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authorative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status', // RFC 4918
        208 => 'Already Reported', // RFC 5842
        226 => 'IM Used', // RFC 3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324
        421 => 'Misdirected Request', // RFC7540 (HTTP/2)
        422 => 'Unprocessable Entity', // RFC 4918
        423 => 'Locked', // RFC 4918
        424 => 'Failed Dependency', // RFC 4918
        426 => 'Upgrade Required',
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        451 => 'Unavailable For Legal Reasons', // draft-tbray-http-legally-restricted-status
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage', // RFC 4918
        508 => 'Loop Detected', // RFC 5842
        509 => 'Bandwidth Limit Exceeded', // non-standard
        510 => 'Not extended',
        511 => 'Network Authentication Required', // RFC 6585
    ];

    public function setStatus($code = 200, $message = null) {
        if(null != $code) {
            $this->statusCode = $code;
            if(null == $message) {
                if(array_key_exists($code, self::$statusCodes)) {
                    $this->statusText = self::$statusCodes[$code];
                }
            } else
                $this->statusText = $message;
        }
        return $this;
    }

    public function setHeaders($headers = []) {
        $this->headers = $headers;
        return $this;
    }

    public function setBody($body = '') {
        $this->responseBody = $body;
        return $this;
    }

    public function json($data) {
        $this->responseHeaders['Content-Type'] = 'application/json';
        $this->responseBody = \json_encode($data);

        return $this;
    }

    public function send() {
        foreach($this->responseHeaders as $header => $value) {
            header($header . ":". $value, true);
        }
        header('HTTP/1.1 ' . $this->statusCode . ' ' . $this->statusText);
        echo $this->responseBody;
        exit();
    }
}