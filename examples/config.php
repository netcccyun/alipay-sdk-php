<?php

/**
 * 支付宝应用信息配置文件
 * 参考文档：https://opendocs.alipay.com/common/02kipl
 */
$alipay_config = [

     //=======【应用基本信息设置】=======
     /**
      * 支付宝开放平台应用ID
      */
     'app_id' => '',

     /**
      * 是否使用公钥证书模式
      */
     'cert_mode' => 0,

     /**
      * 支付宝公钥
      * （使用公钥证书模式此项可留空）
      */
     'alipay_public_key' => '',

     /**
      * 应用私钥
      */
     'app_private_key' => '',

     /**
      * 服务商模式应用授权token
      * 只有服务商模式的子商户需要填写
      */
     //'app_auth_token' => '',

     /**
      * 互联网平台直付通子商户ID
      * 只有互联网平台直付通的子商户需要填写
      */
     //'smid' => '',


     //=======【证书路径设置，使用公钥证书模式需填写】=======
     /**
      * 应用公钥证书文件路径
      * （填写后会使用公钥证书模式，留空使用公钥模式）
      */
     'app_cert_path' => '',

     /**
      * 支付宝公钥证书文件路径
      * （填写后会使用公钥证书模式，留空使用公钥模式）
      */
     'alipay_cert_path' => '',

     /**
      * 支付宝根证书文件路径
      * （填写后会使用公钥证书模式，留空使用公钥模式）
      */
     'root_cert_path' => '',


     //=======【其他设置，一般无需修改】=======
     /**
      * 签名方式,默认为RSA2
      */
     'sign_type' => "RSA2",

     /**
      * 编码格式
      */
     'charset' => "UTF-8",

     /**
      * 支付宝网关
      */
     'gateway_url' => "https://openapi.alipay.com/gateway.do",

];
return $alipay_config;
