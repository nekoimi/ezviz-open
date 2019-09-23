<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/23 21:47
 * #                            ------
 **/

namespace App;
use YsOpen\Kernel\Contracts\ConsumerHandlerInterface;

class ConsumerHandler implements ConsumerHandlerInterface {

    /**
     * 消费莹石云获取的消息
     *
     * @param string $consumerId 消费者在组中唯一的标识
     * @param array $message 消息体
     * @param string $groupId 消息组
     * @param callable|null $commitCallback
     */
    public function handle(string $consumerId, array $message, string $groupId = 'group1', callable $commitCallback = null) {
        echo sprintf(
            "ConsumerId : %s,  Message : %s, GroupId : %s",
            $consumerId, json_encode($message, JSON_UNESCAPED_UNICODE), $groupId
            ) . PHP_EOL;
        // Commit
        $commitCallback();
    }
}