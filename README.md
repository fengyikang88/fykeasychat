# FykEasyChat
Some quick interfaces to handle WeChat HxChat

## Requirement

1. PHP >= 7.0
2. **[Composer](https://getcomposer.org/)**
3. fileinfo 拓展（素材管理模块需要用到）
4. WeChat公众号授权、小程序分享码、发送订阅消息、环信即时聊天、微信健康卡开放平台
5. 个人封装自用，如有问题，请issues或者pr

## Installation

```shell
$ composer require fengyikang88/fykeasychat
```

基本使用（以服务端生成待参数的小程序二维码为例）:

```php
<?php
use FykEasyChat\WechatQrcode;

    /**
     * Generate Mini Program QR Code
     */
    public function QrCode()
    {
        $config =[
            'app_id'  => '',// 小程序app_id
            'secret'  => '', //小程序app_secret
        ];
        $mod = new WechatQrcode($config);
        $base64_image =  $mod->miniCircularImg('pages/index/index?qrcode=helloworld');
   
        return $base64_image;
        
    }
 
```
微信健康卡开放平台生成健康卡二维码:


```php
<?php
use FykEasyChat\HealthOpen;


    //微信电子健康卡开放平台账号
    private $config = [
        'appId' => '',//开发者ID(AppID)
        'appSecret' => '',//开发者密码(AppSecret)
        'hospitalId' => '', //医院ID
        'paTid' => '',
    ];
        
    /**
     * 获取token
     * @return array
     * @author fyk
     * Time 2021/1/18
     */
    public function getAppToken()
    {
        $wechat = new HealthOpen($this->config);
        try {
            $res = $wechat->getAppToken();
            $result = json_decode($res,true);
            if($result['commonOut']['resultCode'] == 0){
                $ret['requestId'] = $result['commonOut']['requestId'];
                $ret['appToken'] = $result['rsp']['appToken'];
                //存入缓存方便调取
                $redis = new RedisCache();
                $redis->set('HealthAppToken',$ret['appToken'],7100);
                return $ret['appToken'];
            }else{
                throw new BusinessException(ErrorCode::Health_SERVER_ERROR, $result['commonOut']['errMsg']);
            }
        } catch (\Exception $e) {
            throw new BusinessException($e->getMessage());
        }

    }
 
```
