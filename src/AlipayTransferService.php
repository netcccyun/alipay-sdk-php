<?php

namespace Alipay;

/**
 * 支付宝转账服务类
 * @see https://opendocs.alipay.com/open/309/106235
 */
class AlipayTransferService extends AlipayService
{
    /**
     * @param $config 支付宝配置信息
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * 转账到支付宝账号
     * @param $out_biz_no 商户转账唯一订单号
     * @param $amount 转账金额
     * @param $is_userid 收款方是否支付宝userid
     * @param $payee_account 收款方账户
     * @param $payee_real_name 收款方姓名
     * @param $payer_show_name 付款方显示姓名
     * @return mixed {"out_biz_no":"商户订单号","order_id":"支付宝转账订单号","pay_fund_order_id":"支付宝支付资金流水号","status":"SUCCESS","trans_date":"订单支付时间"}
     */
    public function transferToAccount($out_biz_no, $amount, $is_userid, $payee_account, $payee_real_name, $payer_show_name)
    {
        if ($this->isCertMode) {
            $apiName = 'alipay.fund.trans.uni.transfer';
            $payee_type = $is_userid?'ALIPAY_USER_ID':'ALIPAY_LOGON_ID';
            $bizContent = [
                'out_biz_no' => $out_biz_no, //商户转账唯一订单号
                'trans_amount' => $amount, //转账金额
                'product_code' => 'TRANS_ACCOUNT_NO_PWD',
                'biz_scene' => 'DIRECT_TRANSFER',
                'order_title' => $payer_show_name, //付款方显示名称
                'payee_info' => array('identity' => $payee_account, 'identity_type' => $payee_type),
            ];
            if(!empty($payee_real_name))$bizContent['payee_info']['name'] = $payee_real_name; //收款方真实姓名
        } else {
            $apiName = 'alipay.fund.trans.toaccount.transfer';
            $payee_type = $is_userid?'ALIPAY_USERID':'ALIPAY_LOGONID';
            $bizContent = [
                'out_biz_no' => $out_biz_no, //商户转账唯一订单号
                'payee_type' => $payee_type, //收款方账户类型
                'payee_account' => $payee_account, //收款方账户
                'amount' => $amount, //转账金额
                'payer_show_name' => $payer_show_name, //付款方显示姓名
            ];
            if(!empty($payee_real_name))$bizContent['payee_real_name'] = $payee_real_name; //收款方真实姓名
        }

        $result = $this->aopExecute($apiName, $bizContent);
        if(isset($result['pay_date'])) $result['trans_date'] = $result['pay_date'];
        return $result;
    }

    /**
     * 转账到银行卡账户
     * @param $out_biz_no 商户转账唯一订单号
     * @param $amount 转账金额
     * @param $payee_account 收款方账户
     * @param $payee_real_name 收款方姓名
     * @param $payer_show_name 付款方显示姓名
     * @return mixed {"out_biz_no":"商户订单号","order_id":"支付宝转账订单号","pay_fund_order_id":"支付宝支付资金流水号","status":"SUCCESS","trans_date":"订单支付时间"}
     */
    public function transferToBankCard($out_biz_no, $amount, $payee_account, $payee_real_name, $payer_show_name)
    {
        $apiName = 'alipay.fund.trans.uni.transfer';
        $bizContent = [
            'out_biz_no' => $out_biz_no, //商户转账唯一订单号
            'trans_amount' => $amount, //转账金额
            'product_code' => 'TRANS_BANKCARD_NO_PWD',
            'biz_scene' => 'DIRECT_TRANSFER',
            'order_title' => $payer_show_name, //付款方显示名称
            'payee_info' => array(
                'identity_type' => 'BANKCARD_ACCOUNT',
                'identity' => $payee_account,
                'name' => $payee_real_name,
                'bankcard_ext_info' => array(
                    'account_type' => '2'
                )
            ),
        ];
        return $this->aopExecute($apiName, $bizContent);
    }

    /**
     * 转账单据查询
     * @param $order_id 订单号
     * @param $type 订单号类型(0=支付宝转账单据号,1=支付宝支付资金流水号,2=商户转账唯一订单号)
     * @param $code 产品类型(0=转账到支付宝账户,1=转账到银行卡)
     * @return mixed {"order_id":"支付宝转账单据号","pay_fund_order_id":"支付宝支付资金流水号","out_biz_no":"商户转账唯一订单号","trans_amount":1,"status":"SUCCESS","pay_date":"支付时间","error_code":"PAYEE_CARD_INFO_ERROR","fail_reason":"收款方银行卡信息有误"}
     */
    public function query($order_id, $type=0, $code = 0)
    {
        $apiName = 'alipay.fund.trans.common.query';
        $bizContent = [];
        $bizContent['product_code'] = $code == 1 ? 'TRANS_BANKCARD_NO_PWD' : 'TRANS_ACCOUNT_NO_PWD';
        $bizContent['biz_scene'] = 'DIRECT_TRANSFER';
        if($type==1){
            $bizContent['pay_fund_order_id'] = $order_id;
        }elseif($type==2){
            $bizContent['out_biz_no'] = $order_id;
        }else{
            $bizContent['order_id'] = $order_id;
        }
        return $this->aopExecute($apiName, $bizContent);
    }

    /**
     * 账户余额查询
     * @param $alipay_user_id 支付宝用户ID
     * @return mixed {"available_amount":"账户可用余额","freeze_amount":"实时冻结余额"}
     */
    public function accountQuery($alipay_user_id)
    {
        $apiName = 'alipay.fund.account.query';
        $bizContent = array(
            'alipay_user_id' => $alipay_user_id,
            'account_type' => 'ACCTRANS_ACCOUNT',
        );
        return $this->aopExecute($apiName, $bizContent);
    }


}