<?php

namespace FykEasyChat;

/**
 * Class WechatMini
 * @author fyk
 * @package FykWechat
 */
class WechatMini extends Common
{
    /**
     * @var int
     * 定义错误代码
     * -41001: encodingAesKey 非法
     * -41003: aes 解密失败
     * -41004: 解密后得到的buffer非法
     * -41005: base64加密失败
     * -41016: base64解密失败
     *  40029: 临时登录凭证（code）无效
     *  40037: template_id不正确
     *  41028: form_id不正确，或者过期
     *  41029: form_id已被使用
     *  41030: page不正确
     *  45009: 接口调用超过限额（目前默认每个帐号日调用限额为100万）
     */
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
    public static $Illegalcode = 40029;
    public static $Illtemplateid = 40037;
    public static $Illformid = 41028;
    public static $Useformid = 41029;
    public static $Illpage = 41030;
    public static $Maxuse = 45009;


    protected $app_id;
    protected $secret;
    protected $snsapi;
    private $sessionKey;

    public function __construct($config){

        $this->app_id = $config['app_id'];
        $this->secret = $config['secret'];
        $this->snsapi = $config['snsapi']??'snsapi_userinfo';

    }

    /**
     * Interface GetSessionKey
     * @param $code
     * @return mixed
     * @throws \Exception
     * @author fyk
     * Time 2022/4/28
     */
    public function GetSessionKey($code){
        if(empty($code)){
            throw new \Exception('code error');
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$this->app_id&secret=$this->secret&js_code=$code&grant_type=authorization_code";
        $result = $this->curlGet($url);
        if (!isset($result['session_key'])) {
            throw new \Exception($result['errmsg']);
        }
        $this->sessionKey = $result['session_key'];
        return $result;

    }

    /**
     * Interface GetUserInfo
     * @param $encryptedData
     * @param $iv
     * @param null $sessionKey
     * @return string
     * @throws \Exception
     * @author fyk
     * Time 2022/4/28
     */
    public function GetUserInfo($encryptedData, $iv, $sessionKey = null): string
    {
        if (empty($sessionKey)) {
            $sessionKey = $this->sessionKey;
        }
       $decodeData = "";
       $errorCode = $this->decryptData($encryptedData, $iv, $decodeData, $sessionKey, $this->app_id);
       if ($errorCode != self::$OK) { //如果不为0 则直接返回错误代码
           throw new \Exception('error: ' . $errorCode);
       }
       return $decodeData;

    }

    /**
     * Interface decryptData
     * @param $encryptedData
     * @param $iv
     * @param $data
     * @param $sessionKey
     * @param $wxappid
     * @return int
     * @author fyk
     * Time 2022/4/28
     */
    public static function decryptData($encryptedData, $iv, &$data, $sessionKey, $wxappid): int
    {
        if (strlen($sessionKey) != 24) {
            return self::$IllegalAesKey;
        }
        $aesKey = base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            return self::$IllegalIv;
        }
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            return self::$IllegalBuffer;
        }
        if ($dataObj->watermark->appid != $wxappid) {
            return self::$IllegalBuffer;
        }
        $data = $result;
        return self::$OK;
    }

}