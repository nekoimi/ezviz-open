<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:56
 * #                            ------
 **/


namespace Kernel;


use Kernel\Traits\HttpRequestTrait;
use Kernel\Traits\MacroableTrait;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use YsOpen\Kernel\Contracts\ConsumerHandlerInterface;

/**
 * Class Application
 * @package Kernel
 *
 * @method string getBaseUri()
 * @method string getAppKey()
 * @method string getAppSecret()
 * @method CacheInterface getCacheHandler()
 * @method LoggerInterface getLogHandler()
 * @method ConsumerHandlerInterface getConsumerHandler()
 */
abstract class Application {
    use HttpRequestTrait,
        MacroableTrait;

    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->accessToken->setApp($this);
    }

}