<?php
/**
 * @copyright Copyright(c) 2018 211gou.com
 * @file code_pay.php
 * @brief 码支付接口
 * @author py
 * @date 2018-10-22
 * @version 1
 * @note 支付宝 QQ全免 微信免手续费只需交易额度 无需担心第三方跑路资金直接到账 这里不会瞧不起个人站长 免费帮助个人实现支付后立即通知
 */

 /**
 * @class code_pay
 * @brief 码支付接口
 */
class code_pay extends paymentPlugin
{
	//支付插件名称
    public $name = '码支付';

	/**
	 * @see paymentplugin::getSubmitUrl()
	 */
	public function getSubmitUrl()
	{
		return 'http://api2.fateqq.com:52888/creat_order';//正式地址
	}

	//获取返回url
	public function getCallbackUrl()
	{
		return IUrl::getHost().IUrl::creatUrl("/ucenter/order");;
	}

	/**
	 * @see paymentplugin::notifyStop()
	 */
	public function notifyStop()
	{
		echo "success";
	}

	/**
	 * @see paymentplugin::callback()
	 */
	public function callback($callbackData,&$paymentId,&$money,&$message,&$orderNo){}

	/**
	 * @see paymentplugin::serverCallback()
	 */
	public function serverCallback($callbackData,&$paymentId,&$money,&$message,&$orderNo)
	{
		$IDcode   = Payment::getConfigParam($paymentId,'Signature');
		$md5Code               = $this->createMD5($callbackData,$IDcode);

		//校验md5码 防止篡改数据
		if($callbackData['sign'] == $md5Code && $callbackData['pay_no'])
		{
            $orderNo  = $callbackData['pay_id'];//需要充值的ID 或订单号 或用户名
            $money    = (float)$callbackData['money'];//实际付款金额

            //记录回执流水号
            if(isset($callbackData['pay_no']) && $callbackData['pay_no'])
            {
                $this->recordTradeNo($orderNo,$callbackData['pay_no']);
            }
            return true;
		}
		else
		{
			$message = '校验码不正确';//返回失败 继续补单
		}
		return false;
	}

	/**
	 * @see paymentplugin::getSendData()
	 */
	public function getSendData($payment)
	{
        $return = array(
            "id" => Payment::getConfigParam($payment['M_Paymentid'],"M_PartnerId"),//你的码支付ID
            "pay_id" => $payment['M_OrderNO'], //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
            "type" => 1,//1支付宝支付 3微信支付 2QQ钱包
            "price" => number_format($payment['M_Amount'], 2, '.', ''),//金额100元
            "param" => "",//自定义参数
            "notify_url"=>"",//通知地址
            "return_url"=>$this->getCallbackUrl(),//跳转地址
        ); //构造需要传递的参数
        $return['sign'] = $this->createMD5($return,$payment['Signature']);//数据加密md5(a=1&b=2&c=3替换密钥)将需要构造的参数按首字母排序并拼接成url参数 如首字母相同则依次比对下一个字母此参数跟token参数2选1如果看不懂可传递token参数
        return $return;
	}

    /**
    * @brief 生成md5防篡改码
	* @param array  要加密的原数据
	* @param string id密钥
	× @return string md5码
    */
    private function createMD5($rdata,$idCode)
    {
    	//让数组以键值进行排序
        ksort($rdata);
        reset($rdata);

        $sign = ''; //初始化需要签名的字符为空
        foreach ($rdata AS $key => $val) { //遍历需要传递的参数
            if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
            if ($sign != '') { //后面追加&拼接URL
                $sign .= "&";
            }
            $sign .= "$key=$val"; //拼接为url参数形式
        }
    	return md5($sign.$idCode);
    }

	/**
	 * @param 获取配置参数
	 */
	public function configParam()
	{
		return array(
			'M_PartnerId' => '码支付ID',
			'Signature'   => '码支付密钥',
		);
	}
}