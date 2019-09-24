<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-24 上午8:53
 * #                            ------
 **/

namespace YsOpen\Kernel\Types;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use YsOpen\Kernel\Contracts\ConsumerHandlerInterface;
use YsOpen\Kernel\Exception\ConfigErrorException;

/**
 * Class Config
 * @package YsOpen\Kernel\Types
 */
class Config {

    /**
     * @var string
     */
    private $baseUri = 'https://open.ys7.com';

    /**
     * @var int
     */
    private $timeOut = 30;

    /**
     * @var string
     */
    private $cacheKey = 'mex.ysopen.access.token';

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
    public function __construct(array $config)
    {
        foreach (['timeOut', 'appKey', 'appSecret'] as $confName) {
            if (array_key_exists($confName, $config)) {
                $this->{$confName} = $config[$confName];
            }
        }
        $this->setHandler('cacheHandler', $config, CacheInterface::class);
        $this->setHandler('logHandler', $config, LoggerInterface::class);
        $this->setHandler('consumerHandler', $config, ConsumerHandlerInterface::class);

        foreach (['cacheHandler', 'logHandler', 'consumerHandler'] as $handler) {
            if (is_null($this->{$handler})) {
                throw new ConfigErrorException(
                    sprintf(sprintf("Config err. %s err.", $handler))
                );
            }
        }
    }


    /**
     * @param string $handlerName
     * @param array $handlers
     * @param $interfaceClass
     */
    private function setHandler(string $handlerName, array $handlers, $interfaceClass)
    {
        if (array_key_exists($handlerName, $handlers)) {
            $handlerHandler = $handlers[$handlerName];
            if (is_string($handlerHandler)) {
                if (class_exists($handlerHandler)) {
                    $handlerHandlerNew = new $handlerHandler;
                    if ($handlerHandlerNew instanceof $interfaceClass) {
                        $this->{$handlerName} = $handlerHandlerNew;
                    }
                }
            } elseif (is_object($handlerName)) {
                if ($handlerName instanceof $interfaceClass) {
                    $this->{$handlerName} = $handlerName;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @return int
     */
    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * @return string
     */
    public function getAppKey(): string
    {
        return $this->appKey;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    /**
     * @return CacheInterface
     */
    public function getCacheHandler(): CacheInterface
    {
        return $this->cacheHandler;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogHandler(): LoggerInterface
    {
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