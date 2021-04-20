<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 6.0 for ThinkPhP 6.0
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 [ https://www.dtapp.net ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/liguangchun/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/liguangchun/ThinkLibrary
// | github 仓库地址 ：https://github.com/GC0202/ThinkLibrary
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

namespace DtApp\ThinkLibrary\service\ksyun;

use DtApp\ThinkLibrary\Service;
use Ks3Client;
use Ks3ServiceException;

/**
 * 金山云对象存储
 * https://www.ksyun.com/nv/product/KS3.html
 * Class Ks3Service
 * @package DtApp\ThinkLibrary\service\ksyun
 */
class Ks3Service extends Service
{
    /**
     * @var
     */
    private $accessKeyID, $accessKeySecret, $endpoint, $bucket;

    /**
     * @param string $accessKeyID
     * @return $this
     */
    public function accessKeyID(string $accessKeyID)
    {
        $this->accessKeyID = $accessKeyID;
        return $this;
    }

    /**
     * @param string $accessKeySecret
     * @return $this
     */
    public function accessKeySecret(string $accessKeySecret)
    {
        $this->accessKeySecret = $accessKeySecret;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function endpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param string $bucket
     * @return $this
     */
    public function bucket(string $bucket): self
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @param string $object
     * @param string $filePath
     * @return bool
     */
    public function upload(string $object, string $filePath): ?bool
    {
        require_once(__DIR__ . "/bin/Ks3Client.class.php");
        $client = new Ks3Client($this->accessKeyID, $this->accessKeySecret, $this->endpoint);
        $content = fopen($filePath, 'rb');
        $args = [
            "Bucket" => $this->bucket,
            "Key" => $object,
            "Content" => [
                //要上传的内容
                "content" => $content,//可以是文件路径或者resource,如果文件大于2G，请提供文件路径
                "seek_position" => 0//跳过文件开头?个字节
            ],
            "ACL" => "public-read",//可以设置访问权限,合法值,private、public-read
            "ObjectMeta" => [
                //设置object的元数据,可以设置"Cache-Control","Content-Disposition","Content-Encoding","Content-Length","Content-MD5","Content-Type","Expires"。当设置了Content-Length时，最后上传的为从seek_position开始向后Content-Length个字节的内容。当设置了Content-MD5时，系统会在服务端进行md5校验。
                "Content-Type" => "binay/ocet-stream"
                //"Content-Length"=>4
            ],
            "UserMeta" => [
                //可以设置object的用户元数据，需要以x-kss-meta-开头
                "x-kss-meta-test" => "test"
            ]
        ];
        try {
            $client->putObjectByFile($args);
            return true;
        } catch (Ks3ServiceException $e) {
            return false;
        }
    }
}
