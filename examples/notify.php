<?php
/**
 * 支付宝异步回调页面示例
 */

require __DIR__.'/../vendor/autoload.php';

$alipay_config = require('config.php');
$aop = new \Alipay\AlipayTradeService($alipay_config);

if($aop->check($_POST)) {//验证成功
    //商户订单号
    $out_trade_no = $_POST['out_trade_no'];

    //支付宝交易号
    $trade_no = $_POST['trade_no'];

    //买家支付宝用户ID
    $buyer_id = !empty($_POST['buyer_id']) ? $_POST['buyer_id'] : $_POST['buyer_open_id'];

    //交易金额
    $total_amount = $_POST['total_amount'];

    if($_POST['trade_status'] == 'TRADE_FINISHED') {
        //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        //根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序

        
        //互联网平台直付通-确认结算
        //$aop->settle_confirm($trade_no, $total_amount);
    }

    //验证成功返回
    echo 'success';
}else{
    //验证失败
    echo 'fail';
}
