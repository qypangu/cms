<?php

namespace app\weixin\controller;

use app\common\controller\WeixinAuth;
use think\weixin\wxpay\Api;
use think\weixin\wxpay\Config;
use think\weixin\wxpay\JsApiPay;
use think\weixin\wxpay\data\WxPayUnifiedOrder;

class Pay extends WeixinAuth{
    
    /**
     * |接到订单ID进行微信支付
     */
    public function order(){
        $order_id=input('order_id');
        $where['id']=$order_id;
        $order=model('order')->where($where)->find();
        $this->assign('order',$order);
        //查询商品支付后的操作插件,后期再加进来
        
        $pay_info['out_trade_no']=$order['order_sn'];
        $pay_info['body']=$order['goods_name'];
        $pay_info['attach']=$order['goods_name'];
        $pay_info['fee']=$order['money'];
        $pay_info['goods_tag']=$order['goods_name'];
        
        $this->payinfo($pay_info);
        
        return view();
    }
    /**
     * |订单模块传过来的order_id
     * @param array $info
     */
    private function payinfo($info){
        //查询对应的商户配置信息,因为使用了微信官方的SDK，要把配置传给对象，根据目前的需求，查询ID为1 的配置就行了，有其它的需求再修改
        $where=['id'=>1];
        $field=['mch_id','appid','secret','partner_key','cert_pem','key_pem'];
        $weixin_config=model('weixinConfig')->field($field)->where($where)->find();
        $config = new Config($weixin_config);
        
        $tools = new JsApiPay($config);
        $openId = $tools->GetOpenid();
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($info['body']);
        $input->SetAttach($info['attach']);
        $input->SetOut_trade_no($info['out_trade_no']);
        $input->SetTotal_fee($info['fee']*100); //传过来的数据都是以元为单位的
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time()+600));
        $input->SetGoods_tag($info['goods_tag']);
        
        $notify_url=isset($info['notify_url'])?$info['notify_url']:'http://'.$_SERVER['HTTP_HOST'].url('Weixin/Api/notify'); //这个URL连接未调试
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = Api::unifiedOrder($config, $input);
        if ($order['return_code']=='SUCCESS') {
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $this->assign('jsApiParameters',$jsApiParameters);
            //获取共享收货地址js函数参数
            $editAddress = $tools->GetEditAddressParameters();
            $this->assign('editAddress',$editAddress);;
        }else {
            $this->error("{$order['return_msg']}");
        }
        
    }
    
}
