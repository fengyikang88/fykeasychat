<?php
namespace FykEasyChat;

/**
 * Class WechatQrcode Mini
 * @author fyk
 * @package FykWechat
 */
class WechatQrcode extends Common
{
    protected $app_id;
    protected $secret;

    public function __construct($config){
        $this->app_id = $config['app_id'];
        $this->secret = $config['secret'];
    }

    /**
     * AccessToken
     * @return mixed
     */
    public function  getAccessToken(){
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->app_id}&secret={$this->secret}";
        $json = $this->httpCurl($access_token);
        $res = json_decode($json,true);
        return $res["access_token"];
    }

    /**
     * Small program QR code picture  square base64
     * @param $path : pages/index/index?qrcode=helloworld
     * @param int $width
     * @param array $other
     * @return string
     */
    public function  miniSquareImg($path,$width=150,$other=[]){

        $ACCESS_TOKEN = $this->getAccessToken();
        $url ="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$ACCESS_TOKEN";
        $arr =["path"=>$path, "width"=> $width];
        $param = json_encode(array_merge($arr,$other));
        $result = $this->httpCurl($url, $param,"POST");
        return "data:image/jpeg;base64,".base64_encode( $result );

    }

    /**
     * Small program QR code picture  circular base64
     * @param $path : pages/index/index?qrcode=helloworld
     * @param int $width
     * @param array $other
     * @return string
     */
    public function miniCircularImg($path,$width=150,$other=[]){
        $ACCESS_TOKEN = $this->getAccessToken();
        $url ="https://api.weixin.qq.com/wxa/getwxacode?access_token=$ACCESS_TOKEN";
        $arr = ["path"=>$path, "width"=> $width];
        $param = json_encode(array_merge($arr,$other));
        $result = $this->httpCurl($url, $param,"POST");
        return "data:image/jpeg;base64,".base64_encode( $result );

    }


}