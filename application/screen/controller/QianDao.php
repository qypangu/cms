<?php
namespace app\screen\controller;

class QianDao extends Publics {
    //暂时不用
    public function index(){
		return view();
	}

    public function detail(){
        if (!input('id')) {
            $this->error('访问失败');
            header('Location:/404.html');die;
            exit;
        }
        //输出后台设置内容
        $where_qiandao[] = ['status','=',1];
        $where_qiandao[] = ['id','=',input('id')];
        $qiandao = model('ScreenQiandao')->where($where_qiandao)->find()->toArray();
        if (!$qiandao) {
            $this->error('已禁用');die;
        }
        $qiandao['mobile_url'] = "http://{$_SERVER['HTTP_HOST']}".url('screen/index/index',['id'=>input('id')]);
        /*var_dump($qiandao);
        exit;*/
        $this->assign('qiandao',$qiandao);
        return view();
    }
	public function draw(){
    	if (!input('id')) {
            $this->error('访问失败');
            header('Location:/404.html');die;
            exit;
        }
        //输出后台设置内容
        $where_qiandao[] = ['status','=',1];
        $where_qiandao[] = ['id','=',input('id')];
        $qiandao = model('ScreenQiandao')->where($where_qiandao)->find()->toArray();
        if (!$qiandao) {
            $this->error('已禁用');die;
        }
        $qiandao['mobile_url'] = "http://{$_SERVER['HTTP_HOST']}".url('screen/index/index',['id'=>input('id')]);
        /*var_dump($qiandao);
        exit;*/
        $this->assign('qiandao',$qiandao);
        return view();
	}

    public function getDetail(){
        if (1) {
            //查询该大屏幕的签到人数
            if (!input('id')) {
                $return = ['status','=',400,'info'=>'访问出错'];
                return json($return);
            }
            $where_screen_log[] = ['screen_id','=',input('id')];
            $where_screen_log[] = ['status','=',1];
            $list_screen_log = model('ScreenQiandaoLog')->field("id,nickname,headimgurl,ranking")->where($where_screen_log)->select()->toArray();
            $count_screen_num = count($list_screen_log);
            //默认200，默认图片也可编辑
            $list_fill = $this->screen_fill($count_screen_num);
            $list['list_screen'] = array_merge($list_screen_log,$list_fill);
            /*return json($list);
            exit;*/
            //程序处理得出
            //排名那里为前端提供16秒数据
            $where_screen_log[] = ['qiandao_time','between',[time()-4,time()]];
            //数据库查找情况
            $list_screen = model('ScreenQiandaoLog')->field("id,nickname,headimgurl,ranking")->order('id desc')->limit(0,10)->where($where_screen_log)->select()->toArray();
            /*var_dump($list_screen);
            exit;*/
            if ($list_screen) {
                foreach ($list_screen as $v) {
                    $rankings[] = $v['ranking'];
                }
                array_multisort($rankings,SORT_DESC,$list_screen);
//                $list['list_rank'] = array_reverse(array_slice($list_screen,0,10));
                $list['list_rank'] = $list_screen;
                $return = ['status'=>100,'info'=>'获取数据成功','data'=>$list];
            } else {
                $list['list_rank'] = '';
                $return = ['status'=>100,'info'=>'获取数据成功','data'=>$list];
            }
            return json($return);
        }
    }

    //登录接口
    public function loginCheck(){
        if (request()->isPost()) {
            if (!input('id')) {
                $return = ['status'=>400,'info'=>'验证失败'];
                return json($return);
                exit;
            }
            if (!input('password')) {
                $return = ['status'=>400,'info'=>'验证失败'];
                return json($return);
                exit;
            }
            //验证密码
            $where[] = ['id','=',input('id')];
            $screen = model('ScreenQiandao')->where($where)->find()->toArray();
            if (!$screen) {
                $return = ['status'=>400,'info'=>'无效的id'];
                return json($return);
                exit;
            }
            if ($screen['password'] == (input('password'))) {
                //验证成功存入session
                session('screen_id'.input('id'),md5(input('id')));
                //测试通过后可以注释
                if (!session('screen_id'.input('id'))) {
                    $return = ['status'=>400,'info'=>'异常状态'];
                    return json($return);
                    exit;
                }
                $return = ['status'=>100,'info'=>'验证成功'];
            } else {
                $return = ['status'=>400,'info'=>'验证失败'];
            }
            return json($return);

        }
    }
    /**
     * 批量添加的  //仅在测试中使用
    /*/
     public function addScreen(){
        //模拟url:http://www.wscrm.net/Screen/qian_dao/add_screen.html
        $res = $this->comments(55);
        if ($res) {
            echo '模拟数据添加成功';
        } else {
            echo '模拟数据添加失败';
        }
    }

    //模拟签到  同步的   等手机端做好，改为异步
    public function sign(){
        //暂时做成同步
        if (request()->isPost()) {
            if (!input('id')) {
                $return = ['status'=>400,'info'=>'非法操作'];
                return jsom($return);
                exit;
            }
            $where[] = ['status','=',1];
            $where[] = ['screen_id','=',input('id')];
            $where_log = $where;
            $where_log[] = ['openid','=',cookie('wx_openid')];
            //如果该微信在该大屏已经签到一次，将不能再签到，考虑网络延迟
            if ($this->get_qiandao_log($where_log)) {
                $return = ['status'=>400,'info'=>'您已经签到过'];
            }
            $screen_log = $this->get_qiandao_log($where);
            //注意：暂时还不考虑大屏人数签到上限
            if ($screen_log) {
                //该大屏已有签到数据
                $data['ranking'] = $screen_log->ranking + 1;
            } else {
                //之前无人签到
                $data['ranking'] = 1;
            }
            $data['screen_id'] = input('id');
            //微信获取
            $data['headimgurl'] = input('headimgurl');
            //微信获取
            $data['nickname'] = input('nickname');
            $data['mobile'] = input('mobile');
            $data['sex'] = input('sex');
			$data['other_param'] = input('other_param');
            $data['openid'] = cookie('wx_openid');
            $data['remark'] = input('remark');
            $data['qiandao_time'] = time();
            $res = model('ScreenQiandaoLog')->save($data);
            if ($res) {
                $return = ['status'=>100,'info'=>'签到成功'];
            } else {
                $return = ['status'=>400,'info'=>'签到失败'];
            }
            return json($return);
        }

    }
    //模拟数据 (测试)  《有用的不能删除》
    /*protected function comments($num = 11,$screen_id = 1){
        for($i = 1;$i <= $num;$i++){
            $comment_list[] =
                [
                    'screen_id' => $screen_id,
                    'headimgurl'=>'/static/index/Special/images/wechat/'.rand(1,15).'.jpg',
                    'nickname'=>'昵称'.$i,
                    'mobile'=>'1'.rand(10,99).rand(1000,9999).rand(4444,8888)+rand(1,200),
                    'sex'=>rand(0,2),
                    'ranking'=>$i,
//                    'creat_time'=>time()+rand(-3600,3600),
                ];
        }
        $res = model('ScreenQiandaoLog')->saveAll($comment_list);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }*/

    //不足200个数据将自动填充  //count为
    protected function screen_fill($num,$count = 200,$img = '/static/screen/qiandao/img/init-img.png'){
        if (!is_int($num) or !is_int($count)) {
            return false;
            exit;
        }
        if ($count <= $num && $count > 200) {
            return false;
            exit;
        }
        $nums = $count - $num;
        for ($i = 1;$i <= $nums;$i++) {
            $data[] = [
                'headimgurl' => $img
            ];
        }
        return $data;
    }

    //查询screen_qiandao_log表   独立于sign方法
    public function get_qiandao_log($where) {
        $screen_log = model('ScreenQiandaoLog')
            ->where($where)
            ->field('ranking')
            ->order('id desc')
            ->limit(0,1)
            ->find();
        return $screen_log;
    }

    //已报名用户签到
    public function saveLog(){
         if (request()->isPost()) {
             if (!input('id')) {
                 $return = ['status'=> 400,'info'=> '请求异常'];
                 return json_decode($return);
                 exit;
             }
             //二次保护接口  只有穿透数据才可以访问该接口  万一遇到问题需要紧急处理
             //二次保护内容主要是验证该用户之前是否已经签到过  可能影响排名
             $where[] = ['screen_id','=',input('screen_id')];
             $where[] = ['status','=',1];
             $screen_log = $this->get_qiandao_log($where);
             //注意：暂时还不考虑大屏人数签到上限
             if ($screen_log) {
                 //该大屏已有签到数据
                 $data['ranking'] = $screen_log->ranking + 1;
             } else {
                 //之前无人签到
                 $data['ranking'] = 1;
             }
             $data['status'] = 1;
             $data['qiandao_time'] = time();
             //删除多余条件
             unset($where);
             $where[] = ['id','=',input('id')];
//			 return $where;	
             $res = model('ScreenQiandaoLog')->save($data,$where);
             if ($res) {
                 $return = ['status'=> 100,'info'=> '签到成功'];
             } else {
                 $return = ['sattus'=>400,'已签到过，异常请求'];
             }
             return json($return);
             exit;
         }
    }


}//end class