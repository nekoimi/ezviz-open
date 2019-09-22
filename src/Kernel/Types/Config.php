<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:39
 * #                            ------
 **/


namespace Kernel\Types;

use Kernel\Exception\ConfigErrorException;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Config
 * @package Kernel\Types
 */
class Config {

    /**
     * @var string
     */
    private $baseUri = 'https://open.ys7.com';

    /**
     * @var string
     */
    private $appKey = '';

    /**
     * @var string
     */
    private $appSecret = '';

    /**
     * @var CacheInterface
     */
    private $cacheHandler;

    /**
     * @var LoggerInterface
     */
    private $logHandler;

    /**
     * Config constructor.
     * @param array $config
     * @throws ConfigErrorException
     */
    public function __construct(array $config) {
        if (array_key_exists('baseUri', $config)) {
            $this->baseUri = $config['baseUri'];
        }
        if (array_key_exists('appKey', $config)) {
            $this->appKey = $config['appKey'];
        }
        if (array_key_exists('appSecret', $config)) {
            $this->appSecret = $config['appSecret'];
        }
        if (array_key_exists('cacheHandler', $config)) {
            $cacheHandler = $config['cacheHandler'];
            if (is_string($cacheHandler)) {
                if (class_exists($cacheHandler)) {
                    $cacheNew = new $cacheHandler;
                    if ($cacheNew instanceof CacheInterface) {
                        $this->cacheHandler = $cacheNew;
                    }
                }
            }elseif (is_object($cacheHandler)) {
                if ($cacheHandler instanceof CacheInterface) {
                    $this->cacheHandler = $cacheHandler;
                }
            }
        }
        if (array_key_exists('logHandler', $config)) {
            $logHandler = $config['logHandler'];
            if (is_string($logHandler)) {
                if (class_exists($logHandler)) {
                    $logNew = new $logHandler;
                    if ($logNew instanceof CacheInterface) {
                        $this->logHandler = $logNew;
                    }
                }
            }elseif (is_object($logHandler)) {
                if ($logHandler instanceof CacheInterface) {
                    $this->logHandler = $logHandler;
                }
            }
        }

        if (is_null($this->cacheHandler) || is_null($this->logHandler)) {
            throw new ConfigErrorException(
                sprintf("Config err. cacheHandler or logHandler err.")
            );
        }
    }

    /**
     * @return string
     */
    public function getBaseUri(): string {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri(string $baseUri) {
        $this->baseUri = $baseUri;
    }

    /**
     * @return string
     */
    public function getAppKey(): string {
        return $this->appKey;
    }

    /**
     * @param string $appKey
     */
    public function setAppKey(string $appKey) {
        $this->appKey = $appKey;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string {
        return $this->appSecret;
    }

    /**
     * @param string $appSecret
     */
    public function setAppSecret(string $appSecret) {
        $this->appSecret = $appSecret;
    }

    /**
     * @return CacheInterface
     */
    public function getCacheHandler(): CacheInterface {
        return $this->cacheHandler;
    }

    /**
     * @param CacheInterface $cacheHandler
     */
    public function setCacheHandler(CacheInterface $cacheHandler) {
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogHandler(): LoggerInterface {
        return $this->logHandler;
    }

    /**
     * @param LoggerInterface $logHandler
     */
    public function setLogHandler(LoggerInterface $logHandler) {
        $this->logHandler = $logHandler;
    }

}