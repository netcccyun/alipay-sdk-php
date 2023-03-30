<?php
/**
 * 支付宝电脑&手机网站支付示例
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');
$hostInfo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];

//引入配置文件
$alipay_config = require('config.php');

//异步回调地址和同步跳转地址
$alipay_config['notify_url'] = $hostInfo.dirname($_SERVER['SCRIPT_NAME']).'/notify.php';
$alipay_config['return_url'] = $hostInfo.dirname($_SERVER['SCRIPT_NAME']).'/return.php';

//构造业务参数bizContent
$bizContent = [
    'out_trade_no' => date("YmdHis").rand(111,999), //商户订单号
    'total_amount' => '0.15', //订单金额，单位为元
    'subject' => 'sample subject', //商品的标题
];

//发起支付请求
try{
    $aop = new \Alipay\AlipayTradeService($alipay_config);
    //$aop->directPayParams($bizContent); //互联网平台直付通补充业务参数
    if(preg_match('/(android|iphone|ipod|windows phone)/i', $_SERVER['HTTP_USER_AGENT'])){ //判断UA为手机浏览器
        $html = $aop->wapPay($bizContent);
    }else{
        $html = $aop->pagePay($bizContent);
    }
    echo $html;
}catch(Exception $e){
    echo '支付宝下单失败！'.$e->getMessage();
}
