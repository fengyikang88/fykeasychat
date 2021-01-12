<?php


namespace FykEasyChat;


class Common
{
    /**
     * @return mixed
     * @author fyk
     * Time 2020/12/28
     */
    public function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->app_id&secret=$this->secret";
        $res = json_decode($this->httpCurl($url));

        return $res->access_token;
    }

    /**
     * GET Curl
     * @param $url
     * @param string $data
     * @param string $method
     * @return bool|string
     */
    public function  httpCurl($url, $data='', $method='GET')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * POST
     * @param $url
     * @param $data
     * @param bool $header
     * @param string $method
     * @return mixed
     */
    public function curl($url, $data, $header = false, $method = "POST")
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $ret = curl_exec($ch);
        return $ret;
    }

    /**
     * WX GET
     * @param $url
     * @return mixed
     * @author fyk
     * Time 2020/12/2
     */
    public function curlGet($url){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data,true);
    }


    /**
     * Generate signature algorithm
     * @param $param
     * @param $appSecret
     * @return false|string
     * @author fyk
     * Time 2021/1/12
     */
    public function getSign($param,$appSecret) {
        if(empty($param)){
            return false;
        }
        $str = '';
        $i = 0;
        foreach ($param as $k=>$v) {
            if($i == 0){
                $str = $k.'='.$v;
            }else{
                $str .= '&'.$k.'='.$v;
            }
            $i++;
        }
        $res = $str.$appSecret;

        $sha256 = hash('sha256',$res,true);
        return base64_encode($sha256);
    }

    /**
     * Generate unique UUID algorithm
     * @param int $length
     * @return false|string
     * @throws \Exception
     * @author fyk
     * Time 2021/1/12
     */
    public function uuid($length = 16 )
    {
        if (function_exists('random_bytes')) {
            $uuid = bin2hex(\random_bytes($length));
        } else if (function_exists('openssl_random_pseudo_bytes')) {
            $uuid = bin2hex(\openssl_random_pseudo_bytes($length));
        } else {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $uuid = substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
        }
        return $uuid;
    }
}