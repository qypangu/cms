<?php
namespace app\weixin\controller;

use app\common\controller\AdminAuth;
use think\weixin\Wechat;
class Kefu extends AdminAuth{
    private $wechat;
    protected function init(){
        $this->model_name='weixinUser';
    }
    public function sendAll(){
        //查出所有奖品的详细名称
        $list_lottery_award=model("lotteryAward")->select();
        foreach ($list_lottery_award as $lottery_award){
            $lottery_award_key[$lottery_award['id']]=$lottery_award;
        }
        //查出要发送的用户
        $page=input('page')?:1;
        $size=1;
        
        
         $where_lottery_log[]=['id','>',5000];  //发送的条件要修改一下
         $where_lottery_log[]=['id','<=',10000];  //发送的条件要修改一下
         $where_lottery_log[]=['mobile','<','1'];
//          $where_lottery_log[]=['id','=',1294];
        
        
        
        $list_lottery_log=model("lotteryLog")->where($where_lottery_log)->page($page,$size)->select()->toArray();
        $success=0;
        $total_success=input('total_success')?:0;
        if(count($list_lottery_log)>0){
            foreach ($list_lottery_log as $lottery_log){
                $rs=$this->sendMsg($lottery_log,$lottery_award_key);
                if ($rs==1) {
                    $success=$success+1;
                }
            }
            $total=$page*$size;
            $error=count($list_lottery_log)-$success;
            $total_success=$total_success+$success;
            $url=url("weixin/kefu/send_all",['page'=>$page+1,'total_success'=>$total_success]);
            $msg="第{$page}页，共发送完{$total}条记录，本次成功{$success}条，失败{$error}条，页面接着发送下{$size}条";
//             echo $msg;exit;
            $this->success($msg,$url,'',1);
        }else {
            $total=($page-1)*$size;
            $total_success=input('total_success');
            echo "数据发送完成，发送{$total}条，成功{$total_success}条";
        }
    }
    /**
     * |发送客服信息
     */
    public function sendMsg($lottery_log,$lottery_award_key){
        
//         //根据openid查出对应的信息
//         $openid=input("openid");
//         $where_lottery_log[]=['weixin_id','=',$openid];
//         $lottery_log=model("lotteryLog")->where($where_lottery_log)->find();
        
        $wx_data['touser']=$lottery_log['weixin_id'];
        $wx_data['template_id']='M9fgq7d8aAGN7N_oWDgI22lAZ1a7SNOvQOjlW1gKnGI'; //抽奖结果通知模板
        $wx_data['url']='http://'.$_SERVER['HTTP_HOST'].url('tuanGame/draw/index',[
                                                'lottery_id'=>$lottery_log['lottery_id'],
                                                'special_id'=>$lottery_log['special_id'],
                                                'sale_id'=>'374',
                                                'activity_id'=>'6',
                                                'owner_id'=>$lottery_log['owner_id'],
                                                'order_num'=>$lottery_log['order_num']]);
        $wx_data['data']=[
            'first'=>['value'=>"恭喜你中奖了"],
            'keyword1'=>['value'=>trim($lottery_award_key[$lottery_log['award_id']]['title'])],
            'keyword2'=>['value'=>date('Y-m-d',$lottery_log['create_time'])],
            'remark'=>['value'=>"感谢您参与本次活动，领取奖品请点详情完善中奖资料，进入查看获得的奖品。"]
        ];
//         var_export($wx_data);
        $where['status']=1;
        $options=model("weixinConfig")->field(['token','encodingaeskey','appid','secret'=>'appsecret'])->where($where)->find();
        $this->wechat=new wechat($options);
        $rs=$this->wechat->sendTemplateMessage($wx_data);        
        if ($rs) {
            $ok=1;
            $data_log['content']=var_export($wx_data,true);
        }else{
            $ok=0;
            $data_log['content']=$this->wechat->errCode.':'.$this->wechat->errMsg."[{$lottery_log['id']}]";
        }
        $data_log['app']="weixin";
        $data_log['controller']="kefu";
        $data_log['action']="sendMsg";
        $data_log['create_time']=time();
        model('sysLog')->save($data_log);
        return $ok;
    }
}
