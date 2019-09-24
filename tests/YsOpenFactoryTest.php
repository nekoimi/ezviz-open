<?php

namespace YsOpen\Tests;

use Psr\SimpleCache\InvalidArgumentException;

/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 下午3:07
 * #                            ------
 **/
class YsOpenFactoryTest extends TestCase {

    public function testStart()
    {
        $config = array (
            'appKey'          => '',
            'appSecret'       => '',
            'cacheHandler'    => CacheHandler::class,
            'logHandler'      => LogHandler::class,
            'consumerHandler' => ConsumerHandler::class,
            'timeOut'         => 30
        );
        $application = \YsOpen\YsOpenFactory::messageClient($config);
        try {
            var_dump($application->createConsumer('demo'));
        } catch (\Exception $e) {
            print_r($e->getMessage());
        } catch (InvalidArgumentException $e) {
            print_r($e->getMessage());
        }
    }

}
