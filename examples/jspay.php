<?php
/**
 * 支付宝生活号支付(JS支付)示例
 * 使用此支付类型前，需要在支付宝开放平台应用里面，配置授权回调域名
 */

require __DIR__.'/../vendor/autoload.php';
@header('Content-Type: text/html; charset=UTF-8');
$hostInfo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];

//引入配置文件
$alipay_config = require('config.php');

//支付宝快捷登录并获取openid
$redirect_uri = $hostInfo.$_SERVER['REQUEST_URI'];
try{
    $oauth = new \Alipay\AlipayOauthService($alipay_config);
    if(isset($_GET['auth_code'])){
        $result = $oauth->getToken($_GET['auth_code']);
        $openid = $result['user_id'];
    }else{
        $oauth->oauth($redirect_uri);
    }
}catch(Exception $e){
    echo '支付宝快捷登录失败！'.$e->getMessage();
    exit;
}

//异步回调地址
$alipay_config['notify_url'] = $hostInfo.dirname($_SERVER['SCRIPT_NAME']).'/notify.php';

//构造业务参数bizContent
$bizContent = [
    'out_trade_no' => date("YmdHis").rand(111,999), //商户订单号
    'total_amount' => '0.15', //订单金额，单位为元
    'subject' => 'sample subject', //商品的标题
];
if(!empty($result['user_id'])){
    $bizContent['buyer_id'] = $result['user_id'];
}else{
    $bizContent['buyer_open_id'] = $result['open_id'];
}

//发起支付请求
try{
    $aop = new \Alipay\AlipayTradeService($alipay_config);
    $result = $aop->qrPay($bizContent);
    $alipay_trade_no = $result['trade_no'];
}catch(Exception $e){
    echo '支付宝下单失败！'.$e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
	<title>支付宝支付</title>
    <link href="//cdn.staticfile.org/ionic/1.3.2/css/ionic.min.css" rel="stylesheet" />
</head>
<body>
<div class="bar bar-header bar-light" align-title="center">
	<h1 class="title">支付宝支付</h1>
</div>
<div class="has-header" style="padding: 5px;position: absolute;width: 100%;">
<div class="text-center" style="color: #a09ee5;">
<i class="icon ion-information-circled" style="font-size: 80px;"></i><br>
<span>正在跳转...</span>
<script>
document.body.addEventListener('touchmove', function (event) {
	event.preventDefault();
},{ passive: false });

var tradeNO = '<?php echo $alipay_trade_no?>';

function Alipayready(callback) {
    // 如果jsbridge已经注入则直接调用
    if (window.AlipayJSBridge) {
        callback && callback();
    } else {
        // 如果没有注入则监听注入的事件
        document.addEventListener('AlipayJSBridgeReady', callback, false);
    }
}
function AlipayJsPay() {
	Alipayready(function(){
		AlipayJSBridge.call("tradePay",{
			tradeNO: tradeNO
		}, function(result){
			var msg = "";
			if(result.resultCode == "9000"){
				msg = "支付成功";
                //跳转到支付成功页面
			}else if(result.resultCode == "8000"){
				msg = "正在处理中";
			}else if(result.resultCode == "4000"){
				msg = "订单支付失败";
			}else if(result.resultCode == "6002"){
				msg = "网络连接出错";
			}
			if (msg!="") {
				alert(msg);
			}
		});
	});
}
window.onload = AlipayJsPay();
</script>
</div>
</div>
</body>
</html>