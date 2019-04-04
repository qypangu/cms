<?php
namespace app\screen\controller;
use app\common\controller\WeixinAuth;
class Index extends WeixinAuth {
	protected function init(){
        $this->model_name="screenQiandaoLog";
        cookie("from_url",$_SERVER['REQUEST_URI']);
    }
    public function index(){
	    if (!input('id')) {
	        $this->error('非法请求');die;
            exit;
        }
        //没有签到的情况下还需提供万能表单内容
        $where_qiandao[] = ['id','=',input('id')];
        $qiandao = model('ScreenQiandao')->where($where_qiandao)->field('other_param_config,remark,title')->find()->toArray();
        /*var_dump($qiandao);
        exit;*/
        $wx_user['remark'] = $qiandao['remark'];
        /*var_dump(strstr($qiandao['other_param_config'][0]['value'],'|'));
        exit;*/
        if ($qiandao['other_param_config']) {
            foreach($qiandao['other_param_config'] as $k => $v) {
                if (isset($v['value']) && is_string($v['value']) && strstr($v['value'],'|')) {
                    $qiandao['other_param_config'][$k]['value'] = explode('|',$v['value']);
                }
            }
        }
        //3种情况下均可获取
        $this->assign('list_other',$qiandao);

	    //判断用户是否签到  默认签到$ident_wx = 1  未签到
        $ident_wx = 1;
	    $where_wx_user[] = ['openid','=',cookie('wx_openid')];
        $where_wx_user[] = ['screen_id','=',input('id')];
        $qiandao_log = model('screenQiandaoLog')->where($where_wx_user)->find();
//        $qiandao_log['other_param'] = json_decode($qiandao_log['other_param'],true);
        /*var_dump($qiandao_log0);
        exit;*/
        if ($qiandao_log) {
            if ($qiandao_log['status'] == 0) {
                $ident_wx = 3;
                /*var_dump($qiandao_log->other_param);
                exit;*/
                foreach ($qiandao_log->other_param as $k =>$v) {
                    $other_default[$v['label']] = $v['value'];
                }
                /*var_dump($other_default);
                exit;*/
                $this->assign('other_default',$other_default);
                $this->assign('data',$qiandao_log);
            }else {
                //该用户已经签到
                $ident_wx = 2;
                $this->assign('data',$qiandao_log);

                }
            } else {
            unset($where_wx_user[1]);
            $wx_user = model('WeixinUser')->where($where_wx_user)->field('nickname,sex,headimgurl')->find()->toArray();
            if (!$wx_user) {
                $this->error('微信授权失败，请关闭微信重新打开');die;
            }
//            $wx_user['other_param_config'] = $qiandao['other_param_config'];
            /*var_dump($wx_user);
            exit;*/
            $this->assign('data',$wx_user);
        }

        //给到前端数据
        /*var_dump($ident_wx);
        exit;*/
        $this->assign('ident_wx',$ident_wx);
		return view();
	}

	//
}//end class