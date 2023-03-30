<?php
/**
 * 支付宝调用其他接口示例
 * 使用\Alipay\AlipayService中的aopExecute方法调用自定义接口
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');

$alipay_config = require('config.php');
$aop = new \Alipay\AlipayService($alipay_config);

//接口名称
$apiName = 'ant.merchant.expand.indirect.zft.consult';

//请求参数的集合
$bizContent = [
    'external_id' => '',
    'name' => '',
    'alias_name' => ''
];

try{
    $result = $aop->aopExecute($apiName, $bizContent);
    print_r($result);
}catch(Exception $e){
    echo '错误信息：'.$e->getMessage();
}

