<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:16
 * #                            ------
 **/

namespace YsOpen;

use Kernel\AccessToken;
use Kernel\Application;
use Kernel\Types\Config;
use YsOpen\IntelligenceClient\Client as IntelligenceClient;

/**
 * Class YsOpenFactory
 * @package YsOpen
 *
 * @method static IntelligenceClient intelligenceClient(array $config)
 */
class YsOpenFactory {

    /**
     * @param string $appName
     * @param array $config
     * @return mixed
     * @throws \Kernel\Exception\ConfigErrorException
     * @throws \ReflectionException
     */
    protected static function create(string $appName, array $config) {
        $namespace = ucfirst($appName);
        $applicationClass = "\\YsOpen\\{$namespace}\\Client";
        /**@var Application $application*/
        $application = new $applicationClass;
        $application::mixin(
            new Config($config)
        );
        $accessToken = new AccessToken();
        $application->setAccessToken($accessToken);
        return $application;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Kernel\Exception\ConfigErrorException
     * @throws \ReflectionException
     */
    public static function __callStatic($name, $arguments) {
        return self::create($name, ...$arguments);
    }

}