<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 23:21
 * #                            ------
 **/


namespace Kernel\Traits;

use GuzzleHttp\Client;
use Kernel\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;
use YsOpen\Kernel\Types\ErrorCode;

/**
 * Trait HttpRequestTrait
 * @package Kernel\Traits
 */
trait HttpRequestTrait {

    /**
     *
     * @var int
     */
    static $DEFAULT_TIME_OUT = 5;


    /***
     *
     * @param string $request_uri
     * @param array $query
     * @param array $headers
     * @return array
     * @throws HttpException
     */
    protected function doGet(string $request_uri, array $query = [], array $headers = []) {
        return $this->doRequest('GET', $request_uri, array(
            'headers'   =>  $headers,
            'query'     =>  $query
        ));
    }

    /**
     *
     * @param string $request_uri
     * @param array $data
     * @param array $headers
     * @return array
     * @throws HttpException
     */
    protected function doPost(string $request_uri, array $data = [], array $headers = []) {
        return $this->doRequest('POST', $request_uri, array(
            'headers'   =>  $headers,
            'form_params'   =>  $data
        ));
    }

    /**
     *
     * @param string $method
     * @param string $request_uri
     * @param array $options
     * @return array
     * @throws HttpException
     */
    protected function doRequest(string $method, string $request_uri, $options = []) {
        $options = $this->setDefaultHeader($options);
        return $this->clearResponse(
            $this->newClient(
                $this->getDefaultOptions()
            )->{strtolower($method)}($request_uri, $options)
        );
    }

    /**
     * default options
     * @return array
     */
    protected function getDefaultOptions() {
        return array(
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout'  => method_exists($this, 'getTimeout') ? $this->getTimeout() : static::$DEFAULT_TIME_OUT,
        );
    }

    /**
     * @param array $options
     * @return array
     */
    protected function setDefaultHeader(array $options = []) {
        $headers = &$options['headers'];
        if (! array_key_exists('accessToken', $headers)) {
            $headers['accessToken'] = $this->accessToken->getToken();
        }
        return $options;
    }

    /**
     *
     * @param array $options
     * @return Client
     */
    protected function newClient(array $options = array()) {
        return new Client($options);
    }


    /***
     *
     * @param ResponseInterface $response
     * @return mixed|string
     * @throws HttpException
     */
    protected function clearResponse (ResponseInterface $response) {
        $contents = $response->getBody()->getContents();
        if ($response->getStatusCode() == 200) {
            $results = json_decode($contents, true);
            if (array_key_exists('code', $results)) {
                $resultCode = $results['code'];
                if ($resultCode == 200) {
                    if (array_key_exists('data', $results)) {
                        return $results['data'];
                    }
                    return $results;
                } else {
                    throw new HttpException(
                        sprintf("YsOpen request err. Code: %s, Message: [ %s ]", $resultCode ?? -200, ErrorCode::message($resultCode))
                    );
                }
            }
            throw new HttpException(
                sprintf("YsOpen request err. StatusCode: %s, Message: [ %s ]", $response->getStatusCode(), $results['msg'])
            );
        }
        throw new HttpException(
            sprintf("YsOpen request err. StatusCode: [ %s ]", $response->getStatusCode())
        );
    }

}