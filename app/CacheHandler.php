<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/23 21:12
 * #                            ------
 **/

namespace App;
use Psr\SimpleCache\CacheInterface;

class CacheHandler implements CacheInterface {

    /**
     * @var \Redis
     */
    private $redis;

    public function __construct() {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->auth(base64_encode('yyprisoner@gmail.com'));
        $status = $this->redis->ping();
        echo sprintf("Redis status : [ %s ]", $status) . PHP_EOL;
    }

    public function get($key, $default = null) {
        return $this->redis->get($key) ?? $default;
    }

    public function set($key, $value, $ttl = null) {
        $this->redis->setex($key, $ttl ?? 300, $value);
    }

    public function delete($key) {
        $this->redis->del($key);
    }

    public function clear() {
        $this->redis->del(
            $this->redis->keys('*')
        );
    }

    public function getMultiple($keys, $default = null) {
        // TODO: Implement getMultiple() method.
    }

    public function setMultiple($values, $ttl = null) {
        // TODO: Implement setMultiple() method.
    }

    public function deleteMultiple($keys) {
        // TODO: Implement deleteMultiple() method.
    }

    public function has($key) {
        return false !== $this->redis->get($key);
    }
}