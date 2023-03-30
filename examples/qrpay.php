<?php
/**
 * 支付宝当面付扫码支付示例
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');
$hostInfo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];

//引入配置文件
$alipay_config = require('config.php');

//异步回调地址
$alipay_config['notify_url'] = $hostInfo.dirname($_SERVER['SCRIPT_NAME']).'/notify.php';

//构造业务参数bizContent
$bizContent = [
    'out_trade_no' => date("YmdHis").rand(111,999), //商户订单号
    'total_amount' => '0.15', //订单金额，单位为元
    'subject' => 'sample subject', //商品的标题
];

//发起支付请求
try{
    $aop = new \Alipay\AlipayTradeService($alipay_config);
    $result = $aop->qrPay($bizContent);
    echo '支付宝下单成功！支付二维码链接：'.$result['qr_code'];
}catch(Exception $e){
    echo '支付宝下单失败！'.$e->getMessage();
}
