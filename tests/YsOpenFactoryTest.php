<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 下午3:07
 * #                            ------
 **/
require_once __DIR__ . '/bootstrap.php';

function testStart() {
    $application = \YsOpen\YsOpenFactory::intelligenceClient();
    var_dump($application->getAppKey());
}

testStart();