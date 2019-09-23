<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:56
 * #                            ------
 **/

namespace YsOpen\Kernel;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use YsOpen\Kernel\Contracts\AccessTokenInterface;
use YsOpen\Kernel\Contracts\ConsumerHandlerInterface;
use YsOpen\Kernel\Exception\ConfigErrorException;
use YsOpen\Kernel\Exception\HttpException;
use YsOpen\Kernel\Types\ErrorCode;

/**
 * Class Application
 * @package Kernel
 */
abstract class Application implements AccessTokenInterface {

    /**
     *
     * @var int
     */
    private static $DEFAULT_TIME_OUT = 5;

    /**
     * @var string
     */
    private static $accessTokenUri = 'api/lapp/token/get';

    /**
     * @var string
     */
    private static $cacheKey = 'mex.ysopen.access.token';

    /**
     * @var string
     */
    private static $baseUri = 'https://open.ys7.com';

    /**
     * @var string
     */
    protected $appKey = '';

    /**
     * @var string
     */
    protected $appSecret = '';

    /**
     * @var CacheInterface
     */
    protected $cacheHandler;

    /**
     * @var LoggerInterface
     */
    protected $logHandler;

    /**
     * @var ConsumerHandlerInterface
     */
    protected $consumerHandler;

    /**
     * Application constructor.
     * @param array $config
     * @throws ConfigErrorException
     */
    public function __construct(array $config) {
        foreach ( ['appKey', 'appSecret'] as $confName ) {
            if ( array_key_exists($confName, $config) ) {
                $this->{$confName} = $config[$confName];
            }
        }
        $this->setHandler('cacheHandler', $config, CacheInterface::class);
        $this->setHandler('logHandler', $config, LoggerInterface::class);
        $this->setHandler('consumerHandler', $config, ConsumerHandlerInterface::class);

        if ( is_null($this->cacheHandler) || is_null($this->logHandler) ) {
            throw new ConfigErrorException(
                sprintf("Config err. cacheHandler or logHandler err.")
            );
        }
    }

    /**
     * @param string $handlerName
     * @param array $handlers
     * @param $interfaceClass
     */
    private function setHandler(string $handlerName, array $handlers, $interfaceClass) {
        if ( array_key_exists($handlerName, $handlers) ) {
            $handlerHandler = $handlers[$handlerName];
            if ( is_string($handlerHandler) ) {
                if ( class_exists($handlerHandler) ) {
                    $handlerHandlerNew = new $handlerHandler;
                    if ( $handlerHandlerNew instanceof $interfaceClass ) {
                        $this->{$handlerName} = $handlerHandlerNew;
                    }
                }
            } elseif ( is_object($handlerName) ) {
                if ( $handlerName instanceof $interfaceClass ) {
                    $this->{$handlerName} = $handlerName;
                }
            }
        }
    }


    /**
     * @param bool $refresh
     * @return string
     * @throws HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken(bool $refresh = false): string {
        /**@var CacheInterface $cacheHandler */
        $cacheHandler = $this->cacheHandler;
        if ( !$refresh && $cacheHandler->has(self::$cacheKey) ) {
            return $cacheHandler->get(self::$cacheKey);
        }

        $result = $this->doRequest('POST', self::$accessTokenUri, array (
                'form_params' => [
                    'appKey'    => $this->appKey,
                    'appSecret' => $this->appSecret
                ]
            )
        );

        $accessToken = $result['accessToken'];
        $cacheHandler->set(self::$cacheKey, $accessToken, 6.5*24*3600);

        if ( !$cacheHandler->has(self::$cacheKey) ) {
            throw new RuntimeException('Failed to cache access token.');
        }

        $this->logHandler->info("AccessToken : %s", $accessToken);
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
        $headers = $this->setDefaultHeader($headers);
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
        $headers = $this->setDefaultHeader($headers);
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
            'base_uri' => self::$baseUri,
            'timeout'  => $this->getTimeout() ?? static::$DEFAULT_TIME_OUT,
        );
    }

    /**
     * @return int
     */
    protected function getTimeout() {
        return self::$DEFAULT_TIME_OUT;
    }

    /**
     * @param array $headers
     * @return array
     * @throws HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function setDefaultHeader(array $headers = []) {
        if ( !array_key_exists('accessToken', $headers) ) {
            $headers['accessToken'] = $this->getToken();
        }
        return $headers;
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
        if ( $response->getStatusCode() == 200 ) {
            $results = json_decode($contents, true);
            if ( array_key_exists('code', $results) ) {
                $resultCode = $results['code'];
                if ( $resultCode == 200 ) {
                    if ( array_key_exists('data', $results) ) {
                        return $results['data'];
                    }
                    return $results;
                } else {
                    throw new HttpException(
                        sprintf("YsOpen request err. Code: %s, Message: %s ", $resultCode ?? - 200, ErrorCode::message($resultCode))
                    );
                }
            }
            throw new HttpException(
                sprintf("YsOpen request err. StatusCode: %s, Message: %s ", $response->getStatusCode(), $results['msg'])
            );
        }
        throw new HttpException(
            sprintf("YsOpen request err. StatusCode: %s ", $response->getStatusCode())
        );
    }

}