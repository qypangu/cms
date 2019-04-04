<?php
/**
 * |对单表操作时，系统已自动完成了以下常用的功能操作，
 * |增加add()
 * |删除delete(),永久删除 deleteForever()
 * |修改edit()
 * |查询列表 lists()
 * |查询明细 detail()
 * |数据导出 export()
 * |文件上传 uploadFile()
 */
namespace app\weixin\controller\admin;

use app\common\controller\AdminAuth;
use think\weixin\Wechat;
use think\weixin\Pay;

class User extends AdminAuth{
    private $wechat;
    public function init(){
        $this->model_name="weixin/weixinUser";
    }
    /**
     * |批量更新微信用户信息
     */
    public function batchget(){
        $page=input('page')?:1;
        $list_user=model('weixinUser')->field('openid')->page($page,100)->order("id desc")->select()->toArray();
        if (count($list_user)<1) { //没有数据就跳出
            $this->success("数据更新完毕！",url('lists'));exit;
        }
        foreach ($list_user as $user){
            $openid_arr['user_list'][]=['openid'=>$user['openid']];
        }
        
        $where['status']=1;
        $options=model("weixinConfig")->field(['token','encodingaeskey','appid','secret'=>'appsecret'])->where($where)->find();
        $this->wechat=new wechat($options);
        $list_weixin_user=$this->wechat->getUsersInfo($openid_arr);
        if (is_array($list_weixin_user)) {
            foreach ($list_weixin_user['user_info_list'] as $user){
                if ($user['subscribe']==1) {
                    $where_update[]=['openid','=',$user['openid']];
                    model('weixinUser')->allowField(true)->save($user,$where_update);
                    $where_update='';
                }
            }
            $number=$page*100;
            $this->success("已更新{$number}条数据！",url('batchget',['page'=>$page+1]),'',1);
        }else{
            $this->error("更新失败，错误代码".$this->wechat->errCode.':'.$this->wechat->errMsg.'<br>继续下一批数据',url('batchget',['page'=>$page+1]));
        }
    }
        
    public function test(){
        $where_wx_config[]=['status','=',1];
        $weixin_config=db('weixinConfig')->field(['mch_id','appid'=>'wxappid','partner_key'=>'apikey','cert_pem','key_pem'])->where($where_wx_config)->find();
        $pay=new Pay($weixin_config);
        $order['bill_date']='20190322';
        $rs=$pay->downloadBill($order);
        $filename = date('Ymd').'.csv'; //设置文件名
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public'); 
        echo $rs;
    }
//     protected $beforeActionList=[
//         'city'=>['only'=>'lists'],
//     ];
//     /**
//      * |此功能 后期要深入开发才能更好使用
//      */
//     protected function city(){
//         $list_city=model('weixinUser')->group("city")->column('city');
//         $this->assign('list_city',$list_city);
//         $list_province=model('weixinUser')->group("province")->column('province');
//         $this->assign('list_province',$list_province);
//     }
}//end class
