<?php


namespace FykEasyChat;

/**
 * Subscribe to news
 * @author fyk
 * Class WechatMessage
 * @package FykEasyChat
 * Time 2020-11-04
 */
class WechatMessage extends Common
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
     * @author fyk
     * Time 2020-11-04
     */
    public function  getMiniAccessToken(){
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->app_id}&secret={$this->secret}";
        $json = $this->httpCurl($access_token);
        $res = json_decode($json,true);
        return $res["access_token"];
    }

    /**
     * Publish subscribe message
     * @param $openid
     * @param $template_id
     * @param $page
     * @param $content
     * @return bool|string
     * @author fyk
     * Time 2020-11-04
     */
    public function sendMiniSubscribe($openid,$template_id,$page,$content)
    {
        //access_token
        $access_token = $this->getMiniAccessToken();
        //请求url
        $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $access_token ;
        //发送内容
        $data = [] ;
        //接收者（用户）的 openid
        $data['touser'] = $openid ;
        //所需下发的订阅模板id
        $data['template_id'] = $template_id ;
        //点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
        $data['page'] = $page ;
        //模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
        $data['data'] = $content;
        //跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
        $data['miniprogram_state'] = 'formal' ;

        try{
            return $this->httpCurl($url, json_encode($data),"POST");
        }catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}