<?php
/**
 * 支付宝同步跳转页面示例
 * 只有电脑网站支付/手机网站支付会用到
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');

$alipay_config = require('config.php');
$aop = new \Alipay\AlipayTradeService($alipay_config);

if($aop->check($_POST)) {//验证成功
    //商户订单号
    $out_trade_no = $_POST['out_trade_no'];

    //支付宝交易号
    $trade_no = $_POST['trade_no'];

    //交易金额
    $total_amount = $_POST['total_amount'];

    //验证成功返回
    echo '支付成功，商户订单号：'.$out_trade_no.'，交易金额：'.$total_amount;
}else{
    //验证失败
    echo '签名验证失败';
}
