<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:56
 * #                            ------
 **/


namespace Kernel;


use Kernel\Traits\MacroableTrait;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Application
 * @package Kernel
 *
 * @method string getBaseUri()
 * @method setBaseUri(string $baseUri)
 * @method string getAppKey()
 * @method setAppKey(string $appKey)
 * @method string getAppSecret()
 * @method setAppSecret(string $appSecret)
 * @method CacheInterface getCacheHandler()
 * @method setCacheHandler(CacheInterface $cacheHandler)
 * @method LoggerInterface getLogHandler()
 * @method setLogHandler(LoggerInterface $cacheHandler)
 */
abstract class Application {
    use MacroableTrait;

}