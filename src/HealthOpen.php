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

    
}