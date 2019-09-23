<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:16
 * #                            ------
 **/

namespace YsOpen;

use YsOpen\Kernel\AccessTokenTrait;
use YsOpen\Kernel\Application;
use YsOpen\Kernel\Exception\ConfigErrorException;
use YsOpen\Kernel\Types\Config;
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
     */
    protected static function create(string $appName, array $config) {
        $namespace = ucfirst($appName);
        $applicationClass = "\\YsOpen\\{$namespace}\\Client";
        /**@var Application $application*/
        $application = new $applicationClass($config);
        return $application;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments) {
        return self::create($name, ...$arguments);
    }

}