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
    protected $channelNum;

    public function __construct($config){
        $this->appId = $config['appId'];
        $this->appSecret = $config['appSecret'];
        $this->hospitalId = $config['hospitalId'];
        $this->paTid = $config['paTid']??"";
        $this->channelNum = $config['channelNum']??0; //填入代码，0为微信服务号渠道，1为微信小程序渠道，3为刷脸终端，4为扫码终端
    }

    /**
     * Get interface call credentials appToken interface
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getAppToken()
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenAuth/AuthObj/getAppToken';
        $uuid = $this->uuid();
        $param = [
            "appId" => $this->appId,
            "channelNum" => $this->channelNum,
            "hospitalId" =>$this->hospitalId,
            "requestId"=>$uuid,
            "timestamp"=>time(),
        ];
        $sign = $this->getSign($param,$this->appSecret);
        $parameter = [
            "commonIn" =>[
                "appToken"=> "",
                "requestId"=> $uuid,
                "hospitalId"=> $this->hospitalId,
                "timestamp"=>time(),
                "channelNum"=> $this->channelNum,
                "sign"=> $sign
            ],
            "req"=>[
                "appId"=>$this->appId,
            ]
        ];
        try {
            return $this->curl($url,$parameter);
        }catch (\Exception $e) {
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
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getRegisterHealthCard(string $token,string $wechatCode,string $name,string $gender,string $nation,
                                          string $birthday,string $idNumber,string $idType,string $phone1,string $phone2 = "",string $address = "",string $relation = "")
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/registerHealthCard';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
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
//            "phone2"=> $phone2,
//            "patid"=> $this->paTid
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        // print_r($sign);die;
        //传参
        $data = [
            "commonIn" =>[
                "appToken"=> $token,
                "channelNum"=> $this->channelNum,
                "hospitalId"=> $this->hospitalId,
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

        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Get health card interface through health card authorization code
     * @param string $token
     * @param string $healthCode
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getHealthCardByHealthCode(string $token,string $healthCode)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/getHealthCardByHealthCode';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "healthCode"=>$healthCode,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get the health card interface through the health card QR code
     * @param string $token
     * @param string $qrCodeText
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getHealthCardByQRCode(string $token,string $qrCodeText)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/getHealthCardByQRCode';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "qrCodeText"=>$qrCodeText
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * ID photo OCR interface
     * @param string $token
     * @param string $imageContent
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function getOcrInfo(string $token,string $imageContent)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/ocrInfo';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "imageContent"=>$imageContent,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
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
            "channelNum"=>$this->channelNum,
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
                "channelNum"=> $this->channelNum,
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
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Register face order ID interface
     * @param string $token
     * @param string $name
     * @param string $idCardNumber
     * @return mixed|string
     * @author fyk
     * Time 2021/5/24
     */
    public function registerOrder(string $token,string $name,string $idCardNumber)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/registerOrder';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
            "name"=>$name,
            "idCardNumber"=>$idCardNumber,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "name"=>$name,
                "idCardNumber"=>$idCardNumber,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Verify face recognition data interface
     * @param string $token
     * @param string $orderId
     * @param string $verifyResult
     * @return mixed|string
     * @author fyk
     * Time 2021/5/24
     */
    public function verifyFaceIdentity(string $token,string $orderId,string $verifyResult)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/verifyFaceIdentity';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
            "orderId"=>$orderId,
            "verifyResult"=>$verifyResult,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "orderId"=>$orderId,
                "verifyResult"=>$verifyResult,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Interface registerUniformVerifyOrder
     * @param string $token
     * @param string $idCard
     * @param string $name
     * @param string $cardType
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/8/25
     */
    public function registerUniformVerifyOrder(string $token,string $idCard,string $name,string $cardType = '01')
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/registerUniformVerifyOrder';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
            "cardType"=> $cardType,
            "idCard"=>$idCard,
            "name"=>$name,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "cardType"=> $cardType,
                "idCard"=>$idCard,
                "name"=>$name,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Interface checkUniformVerifyResult
     * @param string $token
     * @param string $verifyOrderId
     * @param string $verifyResult
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/8/25
     */
    public function checkUniformVerifyResult(string $token,string $verifyOrderId,string $verifyResult)
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/TXHealthCard/EHealthCardServer/ISVOpenObj/checkUniformVerifyResult';
        $uuid = $this->uuid();
        $parameter = [
            'appToken'=>$token,
            'requestId'=>$uuid,
            'hospitalId'=>$this->hospitalId,
            'timestamp'=>time(),
            'channelNum'=>$this->channelNum,
            'verifyOrderId'=>$verifyOrderId,
            'verifyResult'=>$verifyResult,
        ];
        ksort($parameter);
        //签名
        $sign = self::getSign($parameter,$this->appSecret);
        //传参
        $data = [
            'commonIn' =>[
                'appToken'=> $token,
                'requestId'=> $uuid,
                'hospitalId'=> $this->hospitalId,
                'timestamp'=>time(),
                'channelNum'=> $this->channelNum,
                'sign'=> $sign,
            ],
            'req'=>[
                'verifyOrderId'=>$verifyOrderId,
                'verifyResult'=>$verifyResult,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Interface reportHISData Card data monitoring interface
     * @param string $token
     * @param string $qrCodeText
     * @param string $time
     * @param string $scene
     * @param string $department
     * @param string $cardType
     * @param string $cardCostTypes
     * @param string $cardChannel
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/11/30
     */
    public function reportHISData(string $token,string $qrCodeText,string $time,string $scene,string $department,string $cardType,string $cardCostTypes,string $cardChannel = '0401')
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/reportHISData';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
            "qrCodeText"=>$qrCodeText,
            "time"=>$time,
            "hospitalCode"=>$this->hospitalId,
            "scene"=>$scene,
            "department"=>$department,
            "cardType"=>$cardType,
            "cardChannel"=>$cardChannel,
            "cardCostTypes"=>$cardCostTypes,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "qrCodeText"=>$qrCodeText,
                "time"=>$time,
                "hospitalCode"=>$this->hospitalId,
                "scene"=>$scene,
                "department"=>$department,
                "cardType"=>$cardType,
                "cardChannel"=>$cardChannel,
                "cardCostTypes"=>$cardCostTypes,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     *  Verify health card QR code interface
     * @param string $token
     * @param string $qrCodeText
     * @param string $terminalId
     * @param string $time
     * @param string $medicalStep
     * @param string $channelCode
     * @param string $hospitalName
     * @param string $useCityCode
     * @param string $useCityName
     * @param string $useType
     * @return mixed|string
     * @throws \Exception
     * @author fyk
     * Time 2021/12/7
     */
    public function verifyQRCode(string $token,string $qrCodeText,string $terminalId,string $time,string $medicalStep,string $channelCode,string $hospitalName,
                                 string $useCityCode = '421100',string $useCityName = '黄冈市',string $useType = '01')
    {
        $url = 'https://p-healthopen.tengmed.com/rest/auth/HealthCard/HealthOpenPlatform/ISVOpenObj/verifyQRCode';
        $uuid = $this->uuid();
        $parameter = [
            "appToken"=>$token,
            "requestId"=>$uuid,
            "hospitalId"=>$this->hospitalId,
            "timestamp"=>time(),
            "channelNum"=>$this->channelNum,
            "qrCodeText"=>$qrCodeText,
            "terminalId"=>$terminalId,
            "time"=>$time,
            "medicalStep"=>$medicalStep,
            "channelCode"=>$channelCode,
            "useCityCode"=>$useCityCode,
            "useCityName"=>$useCityName,
            "hospitalCode"=>$this->hospitalId,
            "hospitalName"=>$hospitalName,
            "useType"=>$useType,
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
                "channelNum"=> $this->channelNum,
                "sign"=> $sign,
            ],
            "req"=>[
                "qrCodeText"=>$qrCodeText,
                "terminalId"=>$terminalId,
                "time"=>$time,
                "medicalStep"=>$medicalStep,
                "channelCode"=>$channelCode,
                "useCityCode"=>$useCityCode,
                "useCityName"=>$useCityName,
                "hospitalCode"=>$this->hospitalId,
                "hospitalName"=>$hospitalName,
                "useType"=>$useType,
            ],
        ];
        //curl
        try {
            return $this->curl($url,$data);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}