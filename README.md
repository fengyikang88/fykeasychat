# FykWechat
Some quick interfaces to handle WeChat

## Requirement

1. PHP >= 7.0
2. **[Composer](https://getcomposer.org/)**
3. fileinfo 拓展（素材管理模块需要用到）

## Installation

```shell
$ composer require "fengyikang88/FykWechat
```

基本使用（以服务端为例）:

```php
<?php
use FykWechat\WechatQrcode;

        $config =[
            'app_id'  => '',// 小程序app_id
            'secret'  => '', //小程序app_secret
        ];
        $mod = new WechatQrcode($config);
        $base64_image =  $mod->miniCircularImg('pages/index/index?qrcode=helloworld');
   
        return $base64_image;
        
 
 
```
