<?php

namespace AppBundle\Manager;

use Buzz\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;

/**
 * HttpManager
 */
class HttpManager
{
    private $buzz;
    private $client;
    private $request;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new \Buzz\Client\Curl();
        $this->client->setTimeout(20);
        $this->buzz = new \Buzz\Browser($this->client);
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Set timeout
     * @param integer $timeout - Timeout is second
     */
    public function setTimeout($timeout)
    {
        $this->client->setTimeout($timeout);
    }

    /**
     * return exception.
     *
     * @param Exception $ex - exception
     *
     * @return array
     */
    private function makeExceptionResponse($ex)
    {
        $statusCode = 500;
        if (strpos($ex->getMessage(), 'Operation timed out') !== false) {
            $statusCode = 504;
        }

        return array(
            'status_code' => $statusCode,
            'content'     => $ex->getMessage()
            );
    }

    /**
     * make appropriate response
     * @param array  $response    - Response
     * @param string $contentType - Content Type
     *
     * @return array
     */
    private function makeResponse($response, $contentType)
    {
        if ($contentType == 'application/json') {
            return array(
                'status_code' => $response->getStatusCode(),
                'content'     => json_decode($response->getContent(), true)
            );
        } else {
            $content = json_decode($response->getContent(), true);

            return array(
                'status_code' => $response->getStatusCode(),
                'content'     => ($content ? : $response->getContent())
            );
        }
    }

    /**
     * Add Basic Auth Listener
     *
     * @param string $username - Username for basic auth
     * @param array  $password - Password for basic auth
     *
     * @return array
     */
    public function addBasicAuthListener($username, $password)
    {
        $this->buzz->addListener(new \Buzz\Listener\BasicAuthListener($username, $password));
    }

    /**
     * Make Http Get call
     *
     * @param string $url         - Resource
     * @param array  $headers     - Http Headers
     * @param array  $params      - Get Parameters
     * @param string $contentType - Content Type
     *
     * @return array
     */
    public function get($url, array $headers = array(), array $params = array(), $contentType = 'application/json')
    {
        try {
            $response = $this->buzz->get($url, $headers, $params);

            return $this->makeResponse($response, $contentType);
        } catch (\HttpException $ex) {
            return $this->makeExceptionResponse($ex);
        } catch (ClientException $ex) {
            return $this->makeExceptionResponse($ex);
        }
    }

    /**
     * Making a post request using buzz.
     *
     * @param string $url         - Resource
     * @param array  $headers     - Http Headers
     * @param array  $params      - Get Parameters
     * @param string $contentType - Content Type
     *
     * @return array
     */
    public function post($url, array $headers = array(), $params = array(), $contentType = 'application/json')
    {
        try {
            $payload = json_encode($params);
            $headers['Content-Type'] = 'application/json';
            $accept = 'application/json';
            $response = $this->buzz->post($url, $headers, $payload, $accept);

            return $this->makeResponse($response, $accept);
        } catch (\HttpException $ex) {
            return $this->makeExceptionResponse($ex);
        } catch (ClientException $ex) {
            return $this->makeExceptionResponse($ex);
        }
    }

    /**
     * Making a patch request using buzz.
     *
     * @param string $url         - Resource
     * @param array  $headers     - Http Headers
     * @param array  $params      - Body
     * @param string $contentType - Content Type
     *
     * @return array
     */
    public function patch($url, array $headers = array(), $params = array(), $contentType = 'application/json')
    {
        try {
            if ($contentType == 'application/x-www-form-urlencoded') {
                $payload = Utility::buildQuery($params);
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
                $accept = '*/*';
            } else {
                $payload = json_encode($params);
                $headers['Content-Type'] = 'application/json';
                $accept = 'application/json';
            }
            $this->appendCidInHeaders($headers);
            $response = $this->buzz->patch($url, $headers, $payload, $contentType);

            return $this->makeResponse($response, $contentType);
        } catch (\HttpException $ex) {
            return $this->makeExceptionResponse($ex);
        } catch (ClientException $ex) {
            return $this->makeExceptionResponse($ex);
        }
    }

    /**
     * Make an Http Request and retry if the request fails.
     *
     * @param string  $url           - Message in Pager Duty
     * @param string  $method        - Method Type
     * @param array   $headers       - Headers
     * @param array   $params        - Params
     * @param integer $timeout       - Timeout
     * @param integer $maxRetry      - Retry Attempts
     * @param string  $contentType   - Content Type
     * @param integer $retryInterval - Retry Interval in seconds.
     *
     * @return array
     */
    public function makeRetriableRequest($url, $method, $headers = array(), $params = array(),
        $timeout = 20, $maxRetry = 1, $contentType = 'application/json', $retryInterval = 0)
    {
        $this->client->setTimeout($timeout);
        $method = strtolower($method);
        do {
            $response = call_user_func_array(
                array($this,$method),
                array($url, $headers, $params, $contentType));
            if (!$this->hasRequestTimedout($response)) {
                break;
            }
            sleep($retryInterval);
        } while ($maxRetry--);

        return $response;
    }

    /**
     * Check If The Http Request Timed Out
     *
     * @param array $response - Http Request Response
     *
     * @return boolean
     */
    public function hasRequestTimedout($response)
    {
        if ($response && $response['status_code'] == 504 &&
            strpos($response['content'], 'Operation timed out') !== false) {
            return true;
        }

        return false;
    }
}
