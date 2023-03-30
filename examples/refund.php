<?php
/**
 * 支付宝交易退款示例
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');

//引入配置文件
$alipay_config = require('config.php');

//构造业务参数bizContent
$bizContent = [
    'trade_no' => '', //支付宝交易号
    'refund_amount' => '0.15', //退款金额，单位为元
    'out_request_no' => date("YmdHis").rand(111,999), //商户退款单号
];

//发起支付请求
try{
    $aop = new \Alipay\AlipayTradeService($alipay_config);
    $result = $aop->refund($bizContent);
    echo '退款成功！退款金额：'.$result['refund_fee'];
}catch(Exception $e){
    echo '退款失败！'.$e->getMessage();
}
