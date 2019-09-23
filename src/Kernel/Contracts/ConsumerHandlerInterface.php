<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 上午9:47
 * #                            ------
 **/

namespace YsOpen\Kernel\Contracts;

/**
 * Interface ConsumerHandlerInterface
 * @package YsOpen\Kernel\Contracts
 *
 * 消息消费者
 */
interface ConsumerHandlerInterface {

    /**
     * 消费莹石云获取的消息
     *
     * @param string $consumerId 消费者在组中唯一的标识
     * @param array $message 消息体
     * @param string $groupId 消息组
     * @param callable|null $commitCallback
     */
    public function handle(string $consumerId, array $message, string $groupId = 'group1', callable $commitCallback = null);

}