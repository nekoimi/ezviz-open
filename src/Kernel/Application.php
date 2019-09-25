<?php
/**
 * ##################################################################################################
 * # ------------Oooo---
 * # -----------(----)---
 * # ------------)--/----
 * # ------------(_/-
 * # ----oooO----
 * # ----(---)----
 * # -----\--(--
 * # ------\_)-
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #
 * #                            ------
 * #    「 涙の雨が頬をたたくたびに美しく 」
 * ##################################################################################################
 */

namespace YsOpen\Kernel;

use YsOpen\Kernel\Contracts\AccessTokenInterface;
use YsOpen\Kernel\Traits\HttpClientTrait;
use YsOpen\Kernel\Traits\MacroableTrait;

/**
 * Class Application
 * @package Kernel
 */
abstract class Application implements AccessTokenInterface
{
    use MacroableTrait,
        HttpClientTrait;
}
