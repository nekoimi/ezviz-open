<?php
/**
 * ##################################################################################################
 * # ------------Oooo---
 * # -----------(----)---
 * # ------------)--/----
 * # ------------(_/-
 * # ----oooO----
 * # ----(---)----
 * # -----\--(--
 * # ------\_)-
 * # ----
 * #     Yprisoner <yyprisoner@gmail.com>
 * #
 * #                            ------
 * #    「 涙の雨が頬をたたくたびに美しく 」
 * ##################################################################################################
 */

namespace YsOpen\IntelligenceClient;

/**
 * Class IntelligenceInterface
 * @package YsOpen\Kernel\Contracts
 *
 * 人脸识别
 * @link https://open.ys7.com/doc/zh/book/index/ai/face.html
 *
 */
interface IntelligenceInterface
{
    /**
     * 创建人脸集合
     *
     * @param string $setName 要创建的人脸集合的名称
     * @return string 创建成功后的人脸集合的唯一标识
     */
    public function createSet(string $setName): string;

    /**
     * 删除人脸集合
     *
     * @param array $setTokens 人脸集合的唯一标识，多个以英文逗号分割,一次最多支持 10 个
     * @return bool
     */
    public function removeSet(array $setTokens): bool;

    /**
     * 通过图片地址分析人脸信息
     *
     * @param string $imageUri 人脸图片地址
     * @param array $options 人脸分析参数
     * @return array   人脸分析返回的接口数据
     */
    public function faceAnalysisByUri(string $imageUri, array $options = []): array;

    /**
     * 通过图片Base64数据分析人脸信息
     *
     * @param string $imageBase64 人脸base64编码数据
     * @param array $options 人脸分析参数
     * @return array 人脸分析返回的接口数据
     */
    public function faceAnalysisByBase64(string $imageBase64, array $options = []): array;

    /**
     * 注册人脸到集合
     *
     * @param array $faceTokens 已检测的人脸唯一标识,多个以,分割,一次最多支持 10 个
     * @param string $setToken 人脸集合的唯一标识
     * @return bool
     */
    public function faceRegisterToSet(array $faceTokens, string $setToken): bool;

    /**
     * 注销人脸数据
     *
     * @param array $faceTokens 已检测的人脸唯一标识,多个以,分割,一次最多支持 10 个
     * @param string $setToken 人脸集合的唯一标识
     * @return bool
     */
    public function faceRemoveFromSet(array $faceTokens, string $setToken): bool;

    /**
     * 通过已检测出人脸的 faceToken 对比人脸相似度
     *
     * @param string $faceToken1
     * @param string $faceToken2
     * @return float
     */
    public function faceCompareByFaceToken(string $faceToken1, string $faceToken2): float;

    /**
     * 通过人脸图片的Base64 对比人脸相似度
     *
     * @param string $imageBase64_1
     * @param string $imageBase64_2
     * @return float
     */
    public function faceCompareByBase64(string $imageBase64_1, string $imageBase64_2): float;

    /**
     * 通过 faceToken 搜索人脸
     *
     * @param string $faceToken 需要检索的人脸 faceToken
     * @param array $setTokens 指定需要检索的人脸集合唯一标识 人脸集合可多个
     * @param int $limit 返回最相似人脸的个数,默认 1 个, 最多返回 5 个
     * @param int $threshold 识别阈值，范围为 0~100 之间，默认 80
     * @param int $matchCount 匹配成功计数，默认为 1 表示匹配成功一次后即结束识别, 0 表示需要识别集合中的所有人脸
     * @return array
     */
    public function faceSearchFromSet(string $faceToken, array $setTokens, int $limit = 1, int $threshold = 80, int $matchCount = 1): array;
}
