<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:16
 * #                            ------
 **/

namespace YsOpen;

use YsOpen\Kernel\Application;
use YsOpen\Kernel\Exception\ConfigErrorException;
use YsOpen\Kernel\Types\Config;
use YsOpen\IntelligenceClient\Client as IntelligenceClient;
use YsOpen\MessageClient\Client as MessageQueueClient;

/**
 * Class YsOpenFactory
 * @package YsOpen
 *
 * @method static IntelligenceClient intelligenceClient(array $config)  AI智能
 * @method static MessageQueueClient messageClient(array $config)       消息通道
 */
class YsOpenFactory {

    /**
     * @param string $appName
     * @param array $config
     * @return Application
     * @throws ConfigErrorException
     * @throws \ReflectionException
     */
    protected static function create(string $appName, array $config): Application {
        $namespace = ucfirst($appName);
        $applicationClass = "\\YsOpen\\{$namespace}\\Client";
        /**@var Application $application*/
        $application = new $applicationClass;
        $application::mixin(
            new Config($config)
        );
        return $application;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Application
     * @throws ConfigErrorException
     * @throws \ReflectionException
     */
    public static function __callStatic($name, $arguments) {
        return self::create($name, ...$arguments);
    }

}