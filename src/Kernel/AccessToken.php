<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/22 22:17
 * #                            ------
 **/


namespace Kernel;

use RuntimeException;
use YsOpen\Kernel\Contracts\AccessTokenInterface;

class AccessToken implements AccessTokenInterface {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $requestMethod = 'POST';

    /**
     * @var string
     */
    protected $accessTokenUri = 'api/lapp/token/get';

    /**
     * @var string
     */
    protected $cachePrefix = 'mex.ysopen.access.token.';

    /**
     * @param Application $app
     */
    public function setApp(Application $app)
    {
        $this->app = $app;
    }


    /**
     * @param bool $refresh
     * @return string
     * @throws Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken(bool $refresh = false): string {
        $cacheKey = $this->getCacheKey();
        $cacheHandler = $this->app->getCacheHandler();
        if (!$refresh && $cacheHandler->has($cacheKey)) {
            return $cacheHandler->get($cacheKey);
        }

        $result = $this->app->doPost(
            $this->accessTokenUri, array (
                'appKey'    =>  $this->app->getAppKey(),
                'appSecret' =>  $this->app->getAppSecret()
            )
        );

        $accessToken = $result['accessToken'];
        $cacheHandler->set($cacheKey, $accessToken, 6.5 * 24 * 3600);

        if (! $cacheHandler->has($cacheKey)) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $accessToken;
    }

    /**
     * @return AccessTokenInterface
     * @throws Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refresh(): AccessTokenInterface {
        $this->getToken(true);

        return $this;
    }

    /**
     * @return string
     */
    protected function getCacheKey () {
        return $this->cachePrefix;
    }

}