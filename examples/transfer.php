<?php
/**
 * 转账到支付宝账户示例
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');

//引入配置文件
$alipay_config = require('config.php');

$out_trade_no = date("YmdHis").rand(111,999); //商户转账唯一订单号
$money = '0.15'; //转账金额，单位：元
$payee_account = ''; //支付宝账户或支付宝Userid
$payee_real_name = ''; //真实姓名（留空则不校验姓名，如果使用支付宝账户则此项必填）
$payer_show_name = '彩虹易支付'; //付款方显示姓名

if(is_numeric($payee_account) && substr($payee_account,0,4)=='2088' && strlen($payee_account)==16)$is_userid = true;
else $is_userid = false;

//发起转账请求
try{
    $transfer = new \Alipay\AlipayTransferService($alipay_config);
	$result = $transfer->transferToAccount($out_trade_no, $money, $is_userid, $payee_account, $payee_real_name, $payer_show_name);
    echo '转账成功！支付宝转账订单号：'.$result['order_id'].' 转账时间：'.$result['trans_date'];
}catch(Exception $e){
    echo $e->getMessage();
    exit;
}