<?php

/**
 * 自定义开发系统插件使用
 * Class adminLogin
 */
class adminLogin extends pluginBase
{
    private $seller_id = 0; //管理员
    private $auto_hidden = "on"; //自动隐藏

    public static function name()
    {
        return "总后台登录提醒";
    }

    public static function description()
    {
        return "管理员登录总部后台时，总部后台首页右下角弹出登录信息【平缘】";
    }

    //配置相关信息
    public static function configName()
    {
        return array(
            "time" => array("name" => "自动关闭弹框", "type" => "select", "value" => array("开" => "on", "关" => "off")),
            "loginInfo" => array("name" => "登录账号展示信息", "type" => "select", "value" => array("用户名" => "admin_name", "邮箱" => "email")),
            "motto" => array("name" => "座右铭", "type" => "text", "value" => "一切美好只是昨日沉醉 淡淡苦涩才是今天滋味"),
        );
    }

    public function reg()
    {
        //视图监听是否存在新订单提醒
        plugin::reg("onFinishView@system@default", $this, 'tipAdminLogins');

        //获取当前管理员登录信息
        plugin::reg("onBeforeCreateAction@system@getAdminLoginInfo", function () {
            self::controller()->getAdminLoginInfo = function () {
                $this->getAdminLoginInfo();
            };
        });
    }

    /**
     * 消息提醒
     */
    public function tipAdminLogins()
    {
        //获取配置信息
        $configData = $this->config();

        //登录信息配置
        $login_info = (isset($configData['loginInfo']) && $configData['loginInfo']) ? $configData['loginInfo'] : 'admin_name';
        $this->seller_id = ($login_info == "admin_name") ? ISafe::get('admin_name') : ISafe::get('admin_email');

        //隐藏开关
        $this->auto_hidden = (isset($configData['time']) && $configData['time']) ? $configData['time'] : 'on';

        //登录时间
        $login_time = ISafe::get('admin_login_time');
        if (ITime::getTime() - strtotime($login_time) < 3){

            //封装展示信息
            $text = "登录账号：" . $this->seller_id . "<br/>";
            $text .= "登录时间：" . $login_time . "<br />";
            $text .= "登录设备：" . IClient::getDevice() . "<br />";
            $text .= "登录地址：" . IClient::getIp() . "<br />";
            $text .= "<a href='' style='color: #EDC764'>{$configData['motto']}</a><br />";

            $randerData = array(
                "ajaxUrl" => IUrl::creatUrl('/system/getAdminLoginInfo'),
                'time' => $login_time,
                'seller_id' => $this->seller_id,
                'content' => $text,
                'auto_hidden' => $this->auto_hidden
            );
            $this->view('adminLogin', $randerData);
        }
    }

    /**
     * 获取用户登录信息
     */
    public function getAdminLoginInfo()
    {
        die(JSON::encode(array('text' => "用户登录信息")));
    }
}