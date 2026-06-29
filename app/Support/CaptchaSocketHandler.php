<?php

namespace App\Support;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class CaptchaSocketHandler
{
    /**
     * Invoke the custom Guzzle handler.
     *
     * @param RequestInterface $request
     * @param array $options
     * @return FulfilledPromise
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        $uri = $request->getUri();
        $host = $uri->getHost();
        $port = $uri->getPort() ?: ($uri->getScheme() === 'https' ? 443 : 80);
        $path = $uri->getPath() ?: '/';
        $query = $uri->getQuery();
        
        $target = $path . ($query ? '?' . $query : '');
        $scheme = $uri->getScheme() === 'https' ? 'ssl://' : '';
        
        // Construct raw HTTP request headers
        $httpHeaders = "";
        foreach ($request->getHeaders() as $name => $values) {
            $httpHeaders .= $name . ": " . implode(", ", $values) . "\r\n";
        }
        
        $requestBody = (string) $request->getBody();
        
        $rawRequest = $request->getMethod() . " " . $target . " HTTP/1.1\r\n" .
                      $httpHeaders .
                      "Content-Length: " . strlen($requestBody) . "\r\n" .
                      "Connection: close\r\n\r\n" .
                      $requestBody;
                      
        // Configure SSL socket contexts if verify is set to false
        $contextOptions = [];
        if (isset($options['verify']) && $options['verify'] === false) {
            $contextOptions['ssl'] = [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ];
        }
        
        $context = stream_context_create($contextOptions);
        
        $timeout = $options['timeout'] ?? 30;
        
        $socket = @stream_socket_client(
            $scheme . $host . ':' . $port,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );
        
        if (!$socket) {
            return new FulfilledPromise(new Response(500, [], json_encode([
                'success' => false,
                'error-codes' => ['socket-connection-failed: ' . $errstr]
            ])));
        }
        
        fwrite($socket, $rawRequest);
        
        $responseString = "";
        while (!feof($socket)) {
            $responseString .= fgets($socket, 8192);
        }
        fclose($socket);
        
        // Parse raw HTTP response
        $parts = explode("\r\n\r\n", $responseString, 2);
        if (count($parts) < 2) {
            $parts = explode("\n\n", $responseString, 2);
        }
        
        $rawHeaders = $parts[0] ?? '';
        $responseBody = $parts[1] ?? '';
        
        $headerLines = explode("\n", str_replace("\r", "", $rawHeaders));
        $statusLine = array_shift($headerLines);
        
        preg_match('{HTTP\/\S*\s(\d{3})}', $statusLine, $match);
        $statusCode = isset($match[1]) ? (int) $match[1] : 200;
        
        $responseHeaders = [];
        foreach ($headerLines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $val) = explode(':', $line, 2);
                $responseHeaders[trim($key)] = trim($val);
            }
        }
        
        return new FulfilledPromise(new Response($statusCode, $responseHeaders, $responseBody));
    }
}
