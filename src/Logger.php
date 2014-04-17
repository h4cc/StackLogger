<?php

/**
 * This file is part of the StackLogger package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Silpion\Stack;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use \Pimple;

use Silpion\Stack\Logger\ContainerBuilder;

/**
 * Stack Middleware for logging requests and responses to a PSR-3 logger.
 *
 * @author Julius Beckmann <beckmann@silpion.de>
 */
class Logger implements HttpKernelInterface
{
    /** @var \Symfony\Component\HttpKernel\HttpKernelInterface */
    private $app;

    /** @var \Pimple */
    private $container;

    /**
     * @param HttpKernelInterface $app
     * @param array $options
     */
    public function __construct(HttpKernelInterface $app, array $options = [])
    {
        $this->app = $app;
        $this->container = $this->setupContainer($options);
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        if ($type !== HttpKernelInterface::MASTER_REQUEST && false == $this->container['log_sub_request']) {
            // Do not log SUB requests.
            return $this->app->handle($request, $type, $catch);
        }

        $this->logRequest($request);

        $response = $this->app->handle($request, $type, $catch);

        $this->logResponse($response, $request);

        return $response;
    }

    private function logRequest(Request $request)
    {
        $msg = sprintf('Request "%s %s"', $request->getMethod(), $request->getRequestUri());

        $this->log($msg, $this->requestToArray($request));
    }

    private function requestToArray(Request $request)
    {
        $map = array(
          'request_method' => $request->getMethod(),
          'request_uri' => $request->getRequestUri(),
          'request_host' => $request->getHost(),
          'request_port' => $request->getPort(),
          'request_scheme' => $request->getScheme(),
          'request_client_ip' => $request->getClientIp(),
          'request_content_type' => $request->getContentType(),
          'request_acceptable_content_types' => $request->getAcceptableContentTypes(),
          'request_etags' => $request->getETags(),
          'request_charsets' => $request->getCharsets(),
          'request_languages' => $request->getLanguages(),
          'request_locale' => $request->getLocale(),
          'request_auth_user' => $request->getUser(),
          'request_auth_has_password' => !is_null($request->getPassword()),
        );
        // Attributes from newer versions.
        if (method_exists($request, 'getEncodings')) {
            $map['request_encodings'] = $request->getEncodings();
        }
        if (method_exists($request, 'getClientIps')) {
            $map['request_client_ips'] = $request->getClientIps();
        }

        return $map;
    }

    private function logResponse(Response $response, Request $request)
    {
        $msg = sprintf(
          'Response %s for "%s %s"',
          $response->getStatusCode(),
          $request->getMethod(),
          $request->getRequestUri()
        );

        $this->log($msg, $this->responseToArray($response));
    }

    private function responseToArray(Response $response)
    {
        return array(
          'response_status_code' => $response->getStatusCode(),
          'response_charset' => $response->getCharset(),
          'response_date' => $response->getDate(),
          'response_etag' => $response->getEtag(),
          'response_expires' => $response->getExpires(),
          'response_last_modified' => $response->getLastModified(),
          'response_max_age' => $response->getMaxAge(),
          'response_protocol_version' => $response->getProtocolVersion(),
          'response_ttl' => $response->getTtl(),
          'response_vary' => $response->getVary(),
        );
    }

    /**
     * Logs a message and given context.
     *
     * @param $msg
     * @param array $context
     */
    private function log($msg, array $context = array())
    {
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->container['logger'];
        $logger->log($this->container['log_level'], $msg, $context);
    }

    /**
     * Builds our internal container and uses options to overwrite.
     *
     * @param array $options
     * @return \Pimple
     */
    private function setupContainer(array $options)
    {
        return (new ContainerBuilder())->process(new Pimple(), $options);
    }
}
