<?php


namespace FykEasyChat;

/**
 * @author fyk
 * Class HealthOpen
 * @package FykEasyChat
 * Time 2021/1/12
 */
class HealthOpen extends Common
{
    protected $appId;
    protected $appSecret;
    protected $hospitalId;
    protected $paTid;

    public function __construct($config){
        $this->appId = $config['appId'];
        $this->appSecret = $config['appSecret'];
        $this->hospitalId = $config['hospitalId'];
        $this->paTid = $config['paTid']??"";
    }

    /**
     * Get interface call credentials appToken interface
     * @param int $channelNum 填入代码，0为微信服务号渠道，1为微信小程序渠道，3为刷脸终端，4为扫码终端
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getAppToken(int $channelNum = 0)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenAuth/AuthObj/getAppToken';
        $uuid = $this->uuid();
        $param = [
            "appId" => $this->appId,
            "channelNum" => 0,
            "hospitalId" =>$this->hospitalId,
            "requestId"=>$uuid,
            "timestamp"=>time(),
        ];
        $sign = $this->getSign($param,$this->appSecret);
        $parameter = [
            "commonIn" =>[
                "appToken"=> "",
                "requestId"=> $uuid,
                "hospitalId"=> "32501",
                "timestamp"=>time(),
                "channelNum"=> 0,
                "sign"=> $sign
            ],
            "req"=>[
                "appId"=>$this->appId,
            ]
        ];
        try {
            return $this->curl($url,$parameter);
<<<<<<< HEAD
        }catch (Exception $e) {
=======
        }catch (\Throwable $e) {
>>>>>>> f373286b6b25a67e21b534d0ca95eda962033594
            return $e->getMessage();
        }

    }

    /**
     * Register health card interface
     * @param string $token appToken
     * @param string $wechatCode 微信身份码
     * @param string $name 姓名
     * @param string $gender 性别
     * @param string $nation 民族
     * @param string $birthday 出生年月日
     * @param string $idNumber 证件号码
     * @param string $idType 证件类型
     * @param string $phone1 联系方式1
     * @param string $phone2 联系方式2
     * @param string $address 地址
     * @param string $relation 家庭关系
     * @param int $channelNum
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getRegisterHealthCard(string $token,string $wechatCode,string $name,string $gender,string $nation,
                                          string $birthday,string $idNumber,string $idType,string $phone1,string $phone2 = "",string $address = "",string $relation = "",int $channelNum = 0)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/registerHealthCard';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$channelNum,
            "wechatCode"=>$wechatCode,
            "name"=> $name,
            "gender"=>$gender,
            "nation"=> $nation,
            "birthday"=> $birthday,
            "idNumber"=>$idNumber,
            "idType"=> $idType,
            "relation"=> $relation,
            "address"=> $address,
            "phone1"=> $phone1,
            "phone2"=> $phone2,
            "patid"=> $this->paTid
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        //传参
        $data = [
            "commonIn" =>[
                "appToken"=> $token,
                "channelNum"=> 0,
                "hospitalId"=> "32501",
                "requestId"=> $uuid,
                "sign"=> $sign,
                "timestamp"=>time(),
            ],
            "req"=>[
                "wechatCode"=>$wechatCode,
                "name"=> $name,
                "gender"=>$gender,
                "nation"=> $nation,
                "birthday"=> $birthday,
                "idNumber"=>$idNumber,
                "idType"=> $idType,
                "relation"=> $relation,
                "address"=> $address,
                "phone1"=> $phone1,
                "phone2"=> $phone2,
                "patid"=> $this->paTid
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);

<<<<<<< HEAD
        }catch (Exception $e) {
=======
        }catch (\Throwable $e) {
>>>>>>> f373286b6b25a67e21b534d0ca95eda962033594
            return $e->getMessage();
        }

    }

    /**
     * Get health card interface through health card authorization code
     * @param string $token
     * @param string $healthCode
     * @param int $channelNum
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getHealthCardByHealthCode(string $token,string $healthCode,int $channelNum = 0)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/getHealthCardByHealthCode';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$channelNum,
            "healthCode"=>$healthCode,
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        //传参
        $data = [
            "commonIn" =>[
                "appToken"=> $token,
                "requestId"=> $uuid,
                "hospitalId"=> $this->hospitalId,
                "timestamp"=>time(),
                "channelNum"=> $channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "healthCode"=>$healthCode,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
<<<<<<< HEAD
        }catch (Exception $e) {
=======
        }catch (\Throwable $e) {
>>>>>>> f373286b6b25a67e21b534d0ca95eda962033594
            return $e->getMessage();
        }
    }

    /**
     * Get the health card interface through the health card QR code
     * @param string $token
     * @param string $qrCodeText
     * @param int $channelNum
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getHealthCardByQRCode(string $token,string $qrCodeText,int $channelNum = 0)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/getHealthCardByQRCode';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$channelNum,
            "qrCodeText"=>$qrCodeText
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        //传参
        $data = [
            "commonIn" =>[
                "appToken"=> $token,
                "requestId"=> $uuid,
                "hospitalId"=> $this->hospitalId,
                "timestamp"=>time(),
                "channelNum"=> $channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "qrCodeText"=>$qrCodeText
            ],
        ];
        //curl
        try {
           return $this->curl($url,$data);
<<<<<<< HEAD
        }catch (Exception $e) {
=======
        }catch (\Throwable $e) {
>>>>>>> f373286b6b25a67e21b534d0ca95eda962033594
            return $e->getMessage();
        }
    }

    /**
     * ID photo OCR interface
     * @param string $token
     * @param string $imageContent
     * @param int $channelNum
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getOcrInfo(string $token,string $imageContent,int $channelNum = 0)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/ocrInfo';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$channelNum,
            "imageContent"=>$imageContent,
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        //传参
        $data = [
            "commonIn" =>[
                "appToken"=> $token,
                "requestId"=> $uuid,
                "hospitalId"=> $this->hospitalId,
                "timestamp"=>time(),
                "channelNum"=> $channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "imageContent"=>$imageContent,
            ],
        ];
        //curl
        try {
           return $this->curl($url,$data);
<<<<<<< HEAD
        }catch (Exception $e) {
=======
        }catch (\Throwable $e) {
>>>>>>> f373286b6b25a67e21b534d0ca95eda962033594
            return $e->getMessage();
        }
    }

    /**
     * Get QR code interface for health card
     * @param string $token
     * @param string $healthCardId
     * @param string $idNumber
     * @param string $idType
     * @param string $codeType
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getDynamicQRCode(string $token,string $healthCardId,string $idNumber,string $idType ="01",string $codeType="1")
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/getDynamicQRCode';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>0,
            "healthCardId"=>$healthCardId,
            "idType"=>$idType,
            "idNumber"=>$idNumber,
            "codeType"=>$codeType,
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        //传参
        $data = [
            "commonIn" =>[
                "appToken"=> $token,
                "requestId"=> $uuid,
                "hospitalId"=> $this->hospitalId,
                "timestamp"=>time(),
                "channelNum"=> 0,
                "sign"=> $sign,
            ],
            "req"=>[
                "healthCardId"=>$healthCardId,
                "idType"=>$idType,
                "idNumber"=>$idNumber,
                "codeType"=>$codeType,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
<<<<<<< HEAD
        }catch (Exception $e) {
=======
        }catch (\Throwable $e) {
>>>>>>> f373286b6b25a67e21b534d0ca95eda962033594
            return $e->getMessage();
        }
    }
}