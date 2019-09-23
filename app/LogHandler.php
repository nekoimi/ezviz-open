<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   2019/9/23 21:36
 * #                            ------
 **/

namespace App;
use Psr\Log\LoggerInterface;

class LogHandler implements LoggerInterface {


    public function emergency($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function alert($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function critical($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function error($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function warning($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function notice($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function info($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function debug($message, array $context = array ()) {
        $this->showLog([$message, $context]);
    }


    public function log($level, $message, array $context = array ()) {
        $this->showLog([$level, $message, $context]);
    }

    private function showLog(array $data) {
        echo sprintf("Log : %s", json_encode((array)$data, JSON_UNESCAPED_UNICODE)) . PHP_EOL;
    }

}