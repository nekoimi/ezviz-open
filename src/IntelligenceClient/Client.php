<?php
/**
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #                   19-9-23 上午10:56
 * #                            ------
 **/

namespace YsOpen\IntelligenceClient;

use YsOpen\Kernel\Application;
use YsOpen\Kernel\Exception\CreateFaceSetFailException;

/**
 * Class Client
 * @package YsOpen\IntelligenceClient
 *
 * AI智能
 */
class Client extends Application implements IntelligenceInterface {

    /**
     * 创建人脸集合
     *
     * @param string $setName 要创建的人脸集合的名称
     * @return string 创建成功后的人脸集合的唯一标识
     * @throws CreateFaceSetFailException
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function createSet(string $setName): string
    {
        $result = $this->doPost('api/lapp/intelligence/face/set/create', array (
            'setName' => $setName
        ));

        if (array_key_exists('setToken', $result)) {
            return $result['setToken'];
        }

        throw new CreateFaceSetFailException(
            json_encode((array)$result, JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * 删除人脸集合
     *
     * @param array $setTokens 人脸集合的唯一标识，多个以英文逗号分割,一次最多支持 10 个
     * @return bool
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function removeSet(array $setTokens): bool
    {
        $this->doPost('api/lapp/intelligence/face/set/delete', array (
            'setTokens' => implode(',', $setTokens)
        ));

        return true;
    }

    /**
     * 通过图片地址分析人脸信息
     *
     * @param string $imageUri 人脸图片地址
     * @param array $options 人脸分析参数
     * @return array   人脸分析返回的接口数据
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceAnalysisByUri(string $imageUri, array $options = []): array
    {
        return $this->doPost('api/lapp/intelligence/face/analysis/detect', array (
            'dataType'  => 0,
            'image'     => $imageUri,
            'operation' => rtrim((string)implode(',', $options), ',') ?? "none"
        ));
    }

    /**
     * 通过图片Base64数据分析人脸信息
     *
     * @param string $imageBase64 人脸base64编码数据
     * @param array $options 人脸分析参数
     * @return array 人脸分析返回的接口数据
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceAnalysisByBase64(string $imageBase64, array $options = []): array
    {
        return $this->doPost('api/lapp/intelligence/face/analysis/detect', array (
            'dataType'  => 1,
            'image'     => $imageBase64,
            'operation' => rtrim((string)implode(',', $options), ',') ?? "none"
        ));
    }

    /**
     * 注册人脸到集合
     *
     * @param array $faceTokens 已检测的人脸唯一标识,多个以,分割,一次最多支持 10 个
     * @param string $setToken 人脸集合的唯一标识
     * @return bool
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceRegisterToSet(array $faceTokens, string $setToken): bool
    {
        $this->doPost('api/lapp/intelligence/face/set/register', array (
            'faceTokens' => implode(',', $faceTokens),
            'setToken'   => $setToken
        ));

        return true;
    }

    /**
     * 注销人脸数据
     *
     * @param array $faceTokens 已检测的人脸唯一标识,多个以,分割,一次最多支持 10 个
     * @param string $setToken 人脸集合的唯一标识
     * @return bool
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceRemoveFromSet(array $faceTokens, string $setToken): bool
    {
        $this->doPost('api/lapp/intelligence/face/set/remove', array (
            'faceTokens' => implode(',', $faceTokens),
            'setToken'   => $setToken
        ));

        return true;
    }

    /**
     * 通过已检测出人脸的 faceToken 对比人脸相似度
     *
     * @param string $faceToken1
     * @param string $faceToken2
     * @return float
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceCompareByFaceToken(string $faceToken1, string $faceToken2): float
    {
        $result = $this->doPost('api/lapp/intelligence/face/analysis/compare', array (
            'dataType'    => 2,
            'imageParam1' => $faceToken1,
            'imageParam2' => $faceToken2
        ));

        if (array_key_exists('score', $result)) {
            return $result['score'];
        }

        return 0;
    }

    /**
     * 通过人脸图片的Base64 对比人脸相似度
     *
     * @param string $imageBase64_1
     * @param string $imageBase64_2
     * @return float
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceCompareByBase64(string $imageBase64_1, string $imageBase64_2): float
    {
        $result = $this->doPost('api/lapp/intelligence/face/analysis/compare', array (
            'dataType'    => 1,
            'imageParam1' => $imageBase64_1,
            'imageParam2' => $imageBase64_2
        ));

        if (array_key_exists('score', $result)) {
            return $result['score'];
        }

        return 0;
    }

    /**
     * 通过 faceToken 搜索人脸
     *
     * @param string $faceToken 需要检索的人脸 faceToken
     * @param array $setTokens 指定需要检索的人脸集合唯一标识 人脸集合可多个
     * @param int $limit 返回最相似人脸的个数,默认 1 个, 最多返回 5 个
     * @param int $threshold 识别阈值，范围为 0~100 之间，默认 80
     * @param int $matchCount 匹配成功计数，默认为 1 表示匹配成功一次后即结束识别, 0 表示需要识别集合中的所有人脸
     * @return array
     * @throws \YsOpen\Kernel\Exception\HttpException
     */
    public function faceSearchFromSet(string $faceToken, array $setTokens, int $limit = 1, int $threshold = 80, int $matchCount = 1): array
    {
        // 初始化搜索条件
        $operation = array ();

        // 生成搜索条件
        array_walk($setTokens, function ($value) use (&$operation, $threshold, $matchCount) {
            $operation[] = [
                'setToken'   => $value,
                'threshold'  => $threshold,
                'matchCount' => $matchCount
            ];
        });

        // 检索
        $result = $this->doPost('api/lapp/intelligence/face/analysis/search', array (
            'dataType'  => 2,
            'image'     => $faceToken,
            'operation' => $operation,
            'topNum'    =>  $limit > 5 ? 5 : $limit
        ));

        if (array_key_exists('results', $result)) {
            return $result['results'];
        }

        return [];
    }

}