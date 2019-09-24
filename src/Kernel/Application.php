<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:56
 * #                            ------
 **/

namespace YsOpen\Kernel;
use YsOpen\Kernel\Contracts\AccessTokenInterface;
use YsOpen\Kernel\Traits\HttpClientTrait;
use YsOpen\Kernel\Traits\MacroableTrait;

/**
 * Class Application
 * @package Kernel
 */
abstract class Application implements AccessTokenInterface {
    use MacroableTrait,
        HttpClientTrait;

}