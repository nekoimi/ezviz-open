<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 上午10:05
 * #                            ------
 **/

namespace YsOpen\MessageClient;

use YsOpen\Kernel\Application;
use YsOpen\Kernel\Exception\CreateConsumerFailException;

/**
 * Class Client
 * @package YsOpen\MessageClient
 */
class Client extends Application implements MessageInterface {

    /**
     * 创建消费者
     *
     * @param string $groupName
     * @return string
     * @throws CreateConsumerFailException
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function createConsumer(string $groupName = 'group1'): string {
        $consumers = $this->doPost("api/lapp/mq/v1/consumer/{$groupName}");
        if (array_key_exists('consumerId', $consumers)) {
            return $consumers['consumerId'];
        }
        throw new CreateConsumerFailException(
            json_encode((array)$consumers, JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * 获取消息
     *
     * @param string $consumerId 消费者Id
     * @param int $preCommit 是否开启自动提交模式
     * @param string $groupId 消费组ID
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function fetchMessage(string $consumerId, int $preCommit = 0, string $groupId = 'group1')
    {
        $message = $this->doPost('api/lapp/mq/v1/consumer/messages', array (
            'consumerId' => $consumerId,
            'preCommit'  => $preCommit
        ));
        // 处理消息并提交
        $this->getConsumerHandler()->handle($consumerId, $message, $groupId,
            function () use ($consumerId)
        {
            $this->commit($consumerId);
        });
    }

    /**
     * @param string $consumerId
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function commit(string $consumerId)
    {
        $this->doPost('api/lapp/mq/v1/consumer/offsets', array (
            'consumerId' => $consumerId
        ));
    }

}