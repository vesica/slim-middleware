<?php

namespace Vesica\Slim\Middleware\Headers;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Validate
{

    private $shouldValidate;
    private $headerName;
    private $headerValue;
    private $errorMessage;

    /**
     * Validate constructor.
     * @param bool $shouldValidate
     * @param string $headerName
     * @param string $headerValue
     * @param string $errorMessage
     */
    public function __construct(bool $shouldValidate,
                                string $headerName,
                                string $headerValue,
                                $errorMessage = 'Sorry, an error occurred'
    )
    {
        $this->shouldValidate = $shouldValidate;
        $this->headerName = $headerName;
        $this->headerValue = $headerValue;
        $this->errorMessage = $errorMessage;

    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if ($this->shouldValidate) {
            if (isset($request->getHeader($this->headerName)[0]) && $request->getHeader($this->headerName)[0] === $this->headerValue) {
                $response = $next($request, $response);

                return $response;
            }

            throw new \Exception($this->errorMessage, 403);
        }

        $response = $next($request, $response);

        return $response;
    }

}