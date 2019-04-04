<?php

namespace app\weixin\controller;

use think\Controller;
use think\weixin\Wechat;

class Api extends Controller{
    private $weObj;
    public function initialize(){
        $id=input('id');
        $where[]=['status','=',1];
        if($id){
            $where[]=['id','=',$id];
        }        
        $options=model("weixinConfig")->field(['token','encodingaeskey','appid','secret'=>'appsecret'])->where($where)->find();
        $this->weObj=new Wechat($options);
        //$this->weObj->valid(); //这个是验证接口的，第一次验证时要开启
        $data['app']="Weixin";
        $data['controller']="Api";
        $data['action']="notify";
        $data['content']=var_export($_SERVER,true);
        $data['update_time']=time();
        $data['create_time']=time();
        model("sysLog")->data($data)->save();
    }
    
    /**
     * 用户授权
     * 当用户无论打开哪个网页，只要没有登录或者说没有wx_openid就跳转到这个接口进行授权
     */
    public function auth(){
        if (input('code')) { //授权
            $rs_access_token=$this->weObj->getOauthAccessToken();
            if ($rs_access_token) {
                cookie('wx_openid',$rs_access_token['openid']); //这个标记用户已授权
                $user_info=model('weixinUser')->where("openid='{$rs_access_token['openid']}'")->find();
                if (!$user_info) { //数据库还没保存的话要去微信处获取
                    $user_info=$this->weObj->getOauthUserinfo($rs_access_token['access_token'],$rs_access_token['openid']);
                    $user_info['add_time']=time();
                    $user_info['public_id']='0';
                    model('weixinUser')->save($user_info);
                }
                $from_url=cookie("from_url");
                if (!$from_url) {
                    $from_url=url("index/Index/index");
                }
                $this->redirect($from_url);
            }else {
                echo $this->weObj->errCode.':'.$this->weObj->errMsg;
            }
            
        }else {
            //记录来源，授权完后要跳回去
            $id=input('id');
            $callback='http://'.$_SERVER['HTTP_HOST'].url('Api/auth',['id'=>$id]);
            $auth_url=$this->weObj->getOauthRedirect($callback);
            $this->redirect($auth_url);
        }
    }
    /**
     * |订单接收微信的通知页面
     * |暂时未做严格的真实身份校验
     */
    public function notify(){
        $post_xml_str=file_get_contents("php://input");
        if (!empty($post_xml_str)) {
            //把微信传过来的数据写入系统日志
            $data['app']="Weixin";
            $data['controller']="Api";
            $data['action']="notify";
            $data['content']=$post_xml_str;
            $data['update_time']=time();
            $data['create_time']=time();
            model("sysLog")->data($data)->save();
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
}
