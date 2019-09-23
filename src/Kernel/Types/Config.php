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
use YsOpen\Kernel\Contracts\ConsumerHandlerInterface;

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
     * @var ConsumerHandlerInterface
     */
    private $consumerHandler;

    /**
     * Config constructor.
     * @param array $config
     * @throws ConfigErrorException
     */
    public function __construct(array $config) {
        $this->setConfig('baseUri', $config);
        $this->setConfig('appKey', $config);
        $this->setConfig('appSecret', $config);
        $this->setHandler('cacheHandler', $config, CacheInterface::class);
        $this->setHandler('logHandler', $config, LoggerInterface::class);
        $this->setHandler('consumerHandler', $config, ConsumerHandlerInterface::class);

        if (is_null($this->cacheHandler) || is_null($this->logHandler)) {
            throw new ConfigErrorException(
                sprintf("Config err. cacheHandler or logHandler err.")
            );
        }
    }


    /**
     * @param string $configName
     * @param array $config
     */
    private function setConfig(string $configName, array $config) {
        if (array_key_exists($configName, $config)) {
            $this->{$configName} = $config[$configName];
        }
    }

    /**
     * @param string $handlerName
     * @param array $handlers
     * @param $interfaceClass
     */
    private function setHandler(string $handlerName, array $handlers, $interfaceClass) {
        if (array_key_exists($handlerName, $handlers)) {
            $handlerHandler = $handlers[$handlerName];
            if (is_string($handlerHandler)) {
                if (class_exists($handlerHandler)) {
                    $handlerHandlerNew = new $handlerHandler;
                    if ($handlerHandlerNew instanceof $interfaceClass) {
                        $this->{$handlerName} = $handlerHandlerNew;
                    }
                }
            }elseif (is_object($handlerName)) {
                if ($handlerName instanceof $interfaceClass) {
                    $this->{$handlerName} = $handlerName;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getBaseUri(): string {
        return $this->baseUri;
    }

    /**
     * @return string
     */
    public function getAppKey(): string {
        return $this->appKey;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string {
        return $this->appSecret;
    }

    /**
     * @return CacheInterface
     */
    public function getCacheHandler(): CacheInterface {
        return $this->cacheHandler;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogHandler(): LoggerInterface {
        return $this->logHandler;
    }

    /**
     * @return ConsumerHandlerInterface
     */
    public function getConsumerHandler(): ConsumerHandlerInterface
    {
        return $this->consumerHandler;
    }

}