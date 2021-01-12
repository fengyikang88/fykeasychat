# FykEasyChat
Some quick interfaces to handle WeChat HxChat

## Requirement

1. PHP >= 7.0
2. **[Composer](https://getcomposer.org/)**
3. fileinfo 拓展（素材管理模块需要用到）
4. WeChat公众号授权、小程序分享码、发送订阅消息、环信即时聊天、微信健康卡开放平台

## Installation

```shell
$ composer require fengyikang88/fykeasychat
```

基本使用（以服务端为例）:

```php
<?php
use FykEasyChat\WechatQrcode;

        $config =[
            'app_id'  => '',// 小程序app_id
            'secret'  => '', //小程序app_secret
        ];
        $mod = new WechatQrcode($config);
        $base64_image =  $mod->miniCircularImg('pages/index/index?qrcode=helloworld');
   
        return $base64_image;
        
 
 
```
