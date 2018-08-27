<?php

/**
 * 自定义开发系统插件使用
 * Class adminLogin
 */
class adminLogin extends pluginBase
{
    private $seller_id = 0; //管理员

    public static function name()
    {
        return "总后台登录提醒";
    }

    public static function description()
    {
        return "管理员登录总部后台时，总部后台首页右下角弹出登录信息【平缘】";
    }

    public function reg()
    {
        //视图监听是否存在新订单提醒
        plugin::reg("onFinishView", $this, 'tipAdminLogins');

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
        //管理员登录
        $this->seller_id = ISafe::get('admin_name');
        $ajaxUrl = IUrl::creatUrl('/system/getAdminLoginInfo');

        $time = ITime::getDateTime();
        $randerData = array(
            "ajaxUrl" => $ajaxUrl,
            'time' => $time,
            'seller_id' => $this->seller_id,
        );
        $this->view('adminLogin', $randerData);
    }

    /**
     * 获取用户登录信息
     */
    public function getAdminLoginInfo()
    {
        $seller_id = IFilter::act(IReq::get('seller_id'), 'string');
        $time = IFilter::act(IReq::get('time'));

        $text = "登录账户：" . $seller_id . "<br/>";
        $text .= "登录时间：" . $time . "<br />";
        $text .= "登录设备：" . IClient::getDevice() . "<br />";
        $text .= "登录地址：" . IClient::getIp() . "<br />";
        die(JSON::encode(array('text' => $text)));
    }
}