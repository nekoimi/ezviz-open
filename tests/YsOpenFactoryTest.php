<?php

namespace YsOpen\Tests;

use App\CacheHandler;
use App\ConsumerHandler;
use App\LogHandler;
use YsOpen\Kernel\Exception\CreateFaceSetFailException;
use YsOpen\Kernel\Exception\HttpException;

/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 下午3:07
 * #                            ------
 **/
class YsOpenFactoryTest extends TestCase {

    public function testStart() {
        $config = array (
            'appKey'          => 'e517a50d9273463cb08b239da6468a66',
            'appSecret'       => 'b9392a2bd656b2fa6561136609f1e344',
            'cacheHandler'    => CacheHandler::class,
            'logHandler'      => LogHandler::class,
            'consumerHandler' => ConsumerHandler::class
        );
        $application = \YsOpen\YsOpenFactory::intelligenceClient($config);
        try {
            print_r($application->createSet('demo'));
        } catch ( \Exception $e ) {
            print_r($e->getMessage());
        }
    }

}
