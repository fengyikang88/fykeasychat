<?php


namespace FykEasyChat;

/**
 * @author fyk
 * Class WechatJssDk
 * @package FykEasyChat
 * Time 2021/1/12
 */
class WechatJssDk extends Common
{
    protected $app_id;
    protected $secret;

    public function __construct($config){
        $this->app_id = $config['app_id'];
        $this->secret = $config['secret'];
    }

    /**
     * @param string $url
     * @return array|string
     * @author fyk
     * Time 2020/12/28
     */
    public  function getParameter($url = "")
    {
        try {
            $jsapiTicket = $this->getJsApiTicket();
            $timestamp = time();
            $nonceStr = $this->createNonceStr();
            // 这里参数的顺序要按照 key 值 ASCII 码升序排序
            $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
            $signature = sha1($string);
            return array(
                "appId"     => $this->app_id,
                "nonceStr"  => $nonceStr,
                "timestamp" => $timestamp,
                "url"       => $url,
                "signature" => $signature,
                "rawString" => $string
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param int $length
     * @return string
     * @author fyk
     * Time 2020/12/28
     */
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * @return mixed
     * @author fyk
     * Time 2020/12/28
     */
    private function getJsApiTicket()
    {
        $accessToken = $this->getAccessToken();
        // 如果是企业号用以下 URL 获取 ticket
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        $res = json_decode($this->httpCurl($url));

        return $res->ticket;
    }



}