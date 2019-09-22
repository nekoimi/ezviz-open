<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:16
 * #                            ------
 **/


namespace YsOpen;


use Kernel\Application;
use Kernel\Types\Config;

/**
 * Class YsOpenFactory
 * @package YsOpen
 */
class YsOpenFactory {

    /**
     * @param string $appName
     * @param array $config
     * @return mixed
     * @throws \Kernel\Exception\ConfigErrorException
     * @throws \ReflectionException
     */
    public static function create(string $appName, array $config) {
        $namespace = ucfirst($appName);
        $applicationClass = "\\YsOpen\\{$namespace}\\Application";
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
     * @return mixed
     * @throws \Kernel\Exception\ConfigErrorException
     * @throws \ReflectionException
     */
    public static function __callStatic($name, $arguments) {
        return self::create($name, ...$arguments);
    }

}