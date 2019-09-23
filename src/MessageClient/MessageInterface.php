<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 上午11:35
 * #                            ------
 **/

namespace YsOpen\MessageClient;

interface MessageInterface {

    /**
     * 创建消费者
     *
     * @param string $groupName
     * @return string
     */
    public function createConsumer(string $groupName): string ;


    /**
     * 获取消息
     *
     * @param string $consumerId 消费者Id
     * @param int $preCommit 是否开启自动提交模式
     * @param string $groupId 消费组ID
     */
    public function fetchMessage(string $consumerId, int $preCommit = 0, string $groupId = 'group1');


    /**
     * @param string $consumerId
     */
    public function commit(string $consumerId);

}