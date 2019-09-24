<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-24 上午9:07
 * #                            ------
 **/

namespace YsOpen\Kernel\Traits;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use YsOpen\Kernel\Exception\HttpException;
use YsOpen\Kernel\Types\ErrorCode;

trait HttpClientTrait {


    /**
     * Clear AccessToken
     */
    public function clearToken() {
        /**@var CacheInterface $cacheHandler */
        $cacheHandler = $this->getCacheHandler();
        $cacheHandler->delete($this->getCacheKey());
    }

    /**
     * @param bool $refresh
     * @return string
     * @throws HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getToken(bool $refresh = false): string {
        /**@var CacheInterface $cacheHandler */
        $cacheHandler = $this->getCacheHandler();
        if ( !$refresh && $cacheHandler->has($this->getCacheKey()) ) {
            return $cacheHandler->get($this->getCacheKey());
        }

        $result = $this->doRequest('POST', 'api/lapp/token/get', array (
                'form_params' => [
                    'appKey'    => $this->getAppKey(),
                    'appSecret' => $this->getAppSecret()
                ]
            )
        );

        $accessToken = $result['accessToken'];
        $this->getLogHandler()->debug(sprintf("AccessToken : %s", $accessToken));
        if (! empty($accessToken)) {
            $cacheHandler->set($this->getCacheKey(), $accessToken, 6.5*24*3600);
        }

        if ( !$cacheHandler->has($this->getCacheKey()) ) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $accessToken;
    }

    /***
     *
     * @param string $request_uri
     * @param array $query
     * @param array $headers
     * @return array
     * @throws HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function doGet(string $request_uri, array $query = [], array $headers = []) {
        $query = $this->setDefaultParams($query);
        return $this->doRequest('GET', $request_uri, array (
            'headers' => $headers,
            'query'   => $query
        ));
    }

    /**
     *
     * @param string $request_uri
     * @param array $data
     * @param array $headers
     * @return array
     * @throws HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function doPost(string $request_uri, array $data = [], array $headers = []) {
        $data = $this->setDefaultParams($data);
        return $this->doRequest('POST', $request_uri, array (
            'headers'     => $headers,
            'form_params' => $data
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
        print_r($options);
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
        return array (
            'base_uri' => $this->getBaseUri(),
            'timeout'  => $this->getTimeOut(),
        );
    }

    /**
     * @param array $params
     * @return array
     * @throws HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function setDefaultParams(array $params = []) {
        if ( !array_key_exists('accessToken', $params) ) {
            $params['accessToken'] = $this->getToken();
        }
        return $params;
    }

    /**
     *
     * @param array $options
     * @return Client
     */
    protected function newClient(array $options = array ()) {
        return new Client($options);
    }


    /***
     *
     * @param ResponseInterface $response
     * @return mixed|string
     * @throws HttpException
     */
    protected function clearResponse(ResponseInterface $response) {
        $contents = $response->getBody()->getContents();
        $this->getLogHandler()->debug($contents);
        if ( $response->getStatusCode() == 200 ) {
            $results = json_decode((string)$contents, true);
            if ( array_key_exists('code', $results) ) {
                $resultCode = $results['code'];
                if ( $resultCode == 200 ) {
                    if ( array_key_exists('data', $results) ) {
                        return $results['data'];
                    }
                    return $results;
                } else {
                    throw new HttpException(
                        sprintf("YsOpen request err. Code: %s, Message: %s ",
                            $resultCode ?? - 200, ErrorCode::message($resultCode))
                    );
                }
            }
            throw new HttpException(
                sprintf("YsOpen request err. StatusCode: %s, Message: %s ",
                    $response->getStatusCode(), $results['msg'])
            );
        }
        throw new HttpException(
            sprintf("YsOpen request err. StatusCode: %s ", $response->getStatusCode())
        );
    }


}