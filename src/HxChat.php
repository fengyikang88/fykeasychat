<?php


namespace FykEasyChat;

/**
 * Class HxChat
 * @author fyk
 * @package FykEasyChat
 */
class HxChat extends Common
{

    protected $app_key;
    protected $client_id;
    protected $client_secret;
    protected $url;


    /**
     * 获取APP管理员Token
     * HxChat constructor.
     */
    function __construct($config)
    {
        $this->app_key = $config['app_key'];
        $this->client_id = $config['client_id'];
        $this->client_secret = $config['client_secret'];
        $this->url = $config['url'];

        $url = $this->url . "/token";
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ];
        $rs = json_decode($this->curl($url, $data), true);
        $this->token = $rs['access_token'];
    }

    /**
     * 注册IM用户(授权注册)
     * @param $username
     * @param $password
     * @param $nickname
     * @return bool|string
     */
    public function hx_register($username, $password, $nickname)
    {
        $url = $this->url . "/users";
        $data = [
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname
        ];
        $header = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, $data, $header, "POST");
    }

    /**
     * 给IM用户的添加好友
     * @param $owner_username
     * @param $friend_username
     * @return bool|string
     */
    public function hx_contacts($owner_username, $friend_username)
    {
        $url = $this->url . "/users/${owner_username}/contacts/users/${friend_username}";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "POST");
    }

    /**
     * 解除IM用户的好友关系
     * @param $owner_username
     * @param $friend_username
     * @return bool|string
     */
    public function hx_contacts_delete($owner_username, $friend_username)
    {
        $url = $this->url . "/users/${owner_username}/contacts/users/${friend_username}";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "DELETE");
    }

    /**
     * 查看好友
     * @param $owner_username
     * @return bool|string
     */
    public function hx_contacts_user($owner_username)
    {
        $url = $this->url . "/users/${owner_username}/contacts/users";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "GET");
    }

    /**
     * 发送文本消息
     * @param $sender
     * @param $receiver
     * @param $msg
     * @return bool|string
     */
    public function hx_send($sender, $receiver, $msg)
    {
        $url = $this->url . "/messages";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        $data = [
            'target_type' => 'users',
            'target' => [
                '0' => $receiver
            ],
            'msg' => [
                'type' => "txt",
                'msg' => $msg
            ],
            'from' => $sender,
            'ext' => [
                'attr1' => 'v1',
                'attr2' => "v2"
            ]
        ];
        return $this->curl($url, $data, $header, "POST");
    }

    /**
     * 查询离线消息数 获取一个IM用户的离线消息数
     * @param $owner_username
     * @return bool|string
     */
    public function hx_msg_count($owner_username)
    {
        $url = $this->url . "/users/${owner_username}/offline_msg_count";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "GET");
    }

    /**
     * 获取IM用户[单个]
     * @param $username
     * @return bool|string
     */
    public function hx_user_info($username)
    {
        $url = $this->url . "/users/${username}";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "GET");
    }

    /**
     * 获取IM用户[批量]
     * @param $limit
     * @return bool|string
     */
    public function hx_user_infos($limit)
    {
        $url = $this->url . "/users?${limit}";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "GET");
    }

    /**
     * 重置IM用户密码
     * @param $username
     * @param $newpassword
     * @return bool|string
     */
    public function hx_user_update_password($username, $newpassword)
    {
        $url = $this->url . "/users/${username}/password";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        $data['newpassword'] = $newpassword;
        return $this->curl($url, $data, $header, "PUT");
    }

    /**
     * 删除IM用户[单个]
     * @param $username
     * @return bool|string
     */
    public function hx_user_delete($username)
    {
        $url = $this->url . "/users/${username}";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        return $this->curl($url, "", $header, "DELETE");
    }

    /**
     * 修改用户昵称
     * @param $username
     * @param $nickname
     * @return bool|string
     */
    public function hx_user_update_nickname($username, $nickname)
    {
        $url = $this->url . "/users/${username}";
        $header = [
            'Authorization: Bearer ' . $this->token
        ];
        $data['nickname'] = $nickname;
        return $this->curl($url, $data, $header, "PUT");
    }

}