<?php
namespace app\common\controller;

use think\weixin\wxpay\Api;
use think\weixin\wxpay\Config;
use think\weixin\wxpay\JsApiPay;
use think\weixin\wxpay\WxPayUnifiedOrder;
use think\exception\ErrorException;

class WeixinAuth extends Pg{
    protected $model_name;
    protected function initialize(){
        $this->init();
        if (!cookie('?wx_openid')) { //跳转到微信认证
            if (input('weixin_auth_id')>0) {
                $id=input('weixin_auth_id');
            }else{ //目前的设计是只有一个微信号是启用的
                $where['status']=1;
                $id=model('weixinConfig')->where($where)->value('id');
            }
            $this->redirect('weixin/Api/auth',['id'=>$id]);exit;
        }        
    }
    protected function init(){
        
    }
    /**
     * |微信通知信息，这个操作要比较严格的验证
     */
    public function notify(){
        $post_xml_str=file_get_contents("php://input");
        if (!empty($post_xml_str)) {
            //把微信传过来的数据写入系统日志
            $data['app']=$this->request->module();
            $data['controller']=$this->request->controller();
            $data['action']="notify";
            $data['content']=$post_xml_str;
            $data['update_time']=time();
            $data['create_time']=time();
            model("sysLog")->data($data)->add();
            //更新订单的支付状态
            $post_obj=simplexml_load_string($post_xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            $post_array=get_object_vars($post_obj);
            if ($post_array['result_code']=='SUCCESS') {
                $where['order_sn']=$post_array['out_trade_no'];
                $order=model("order")->where($where)->find();
                if (is_array($order)&&$order['paystatus']!=1) { //当订单数据是未支付时
                    $data_order['paystatus']=1;
                    $data_order['paytime']=time();
                    $rs=model('order')->where($where)->data($data_order)->save();
                    if ($rs) {
                        echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
                    }
                }
            }
            
        }
    }
    /**
     * |微信支付功能,返回支付相关的数据
     */
    protected function getPayParameter($order,$weixin_config='',$notify_url=''){
        if (!isset($order['body'])) {
            exception("body：请设置商品信息或支付单的说明");
        }
        if (!isset($order['attach'])) {
            exception("attach：请设置需要支付接口返回的数据信息，如订单号order_sn");
        }
        if (!isset($order['out_trade_no'])) {
            exception("out_trade_no：请设置32位的订单号");
        }
        if (!isset($order['fee'])) {
            exception("fee：必需是一个整数");
        }
        //配置信息检查
        if ($weixin_config=='') {
            $where[]=['status','=',1];
            $field=['mch_id','appid','secret','partner_key','cert_pem','key_pem'];
            $weixin_config=model('weixinConfig')->field($field)->where($where)->find();
        } else{
            if (!isset($weixin_config['mch_id'])||!isset($weixin_config['appid'])||!isset($weixin_config['secret'])||!isset($weixin_config['partner_key'])) {
                exception("mch_id、appid、secret、partner_key不能为空");
            }
        }
        $config = new Config($weixin_config);
        
        $tools = new JsApiPay($config);
        $openId = $tools->GetOpenid();
        if (!$openId) { //当没有取得openid时，要跳回来源页面
            $this->error("openid读取异常，请重新授权");
        }
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($order['body']);
        $input->SetAttach($order['attach']);
        $input->SetOut_trade_no($order['out_trade_no']);
        $input->SetTotal_fee($order['fee']*100); //传过来的数据都是以元为单位的
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time()+600));
        $input->SetGoods_tag("{$order['out_trade_no']}:{$order['body']}");
        
        $notify_url=$notify_url?:url('weixin/Api/notify'); //这个URL连接未调试
        $notify_url='http://'.$_SERVER['HTTP_HOST'].$notify_url;
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order_rs = Api::unifiedOrder($config, $input);
        if (isset($order_rs['err_code'])) {
            $this->error("err_code:{$order_rs['err_code']}-{$order_rs['err_code_des']}");
        }else {
            if ($order_rs['return_code']=='SUCCESS') {
                $param['js_api_parameters']=$tools->GetJsApiParameters($order_rs);
                $this->assign('js_api_parameters',$param['js_api_parameters']);
                //获取共享收货地址js函数参数
                $param['edit_address']=$tools->GetEditAddressParameters();
                $this->assign('edit_address',$param['edit_address']);
                return $param;
            }else {
                exception("weixinAuth:{$order_rs['return_msg']}");
            }
        }
        
    }
}
