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
namespace app\screen\controller;

use app\common\controller\Web;

class Draw extends Publics {
    public function init(){
        $this->model_name="screen/screenDraw";
    }
    public function detail() {
        if (!input('screen_draw_id')) {
            $this->error('访问失败');
            header('Location:/404.html');die;
            exit;
        }
        $where_draw_award[] = ['status','=',1];
        $where_draw_award[] = ['screen_id','=',input('screen_draw_id')];
        $list_draw_award = model('ScreenDrawAward')->where($where_draw_award)->order('level asc')->select()->toArray();
        /*var_dump($list_draw_award);
        exit;*/
        $this->assign('list_draw_award',$list_draw_award);
        return view();
    }
    /**
     * |计算并返回中奖用户信息
     */
    public function getLucky(){
        $award_level=input('award_level');
        $get_lucky_count=input('get_lucky_count');
        //判断是否开始抽奖了
        if(session('screen_id')){
            //判断此等奖的是否抽完了，或者传过来想抽奖的人数是否多过未抽的
            
            $list_lucky=model('screenDraw')->getLuck(session('screen_id'),$award_level,$get_lucky_count);
            model('screenDraw')->setLuck($list_lucky,$award_level);
          
            //var_export($list_lucky);
            $this->result($list_lucky);
        }else{
            $this->error("非法操作!error=001");
        }
        
    }
    /**
     * |幸运用户展示
     * |根据传过来的award_level展示对应的列表
     */
    public function LuckyList(){
        $where[]=['status','=',1];
        $where[]=['award_level','=',input('award_level')];
        $where[]=['screen_id','=',session('screen_id')];
        $list_lucky=model('screenDrawLog')->where($where)->select();
        $this->result($list_lucky);
    }

    //登录接口
    public function loginCheck(){
        if (request()->isPost()) {
            if (!input('screen_draw_id')) {
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
            $where[] = ['id','=',input('screen_draw_id')];
            $screen = model('ScreenDraw')->where($where)->find()->toArray();
            if (!$screen) {
                $return = ['status'=>400,'info'=>'无效的id'];
                return json($return);
                exit;
            }
            if ($screen['password'] == (input('password'))) {
                //验证成功存入session
                session('screen_id'.input('screen_draw_id'),md5(input('screen_draw_id')));
                //测试通过后可以注释
                if (!session('screen_draw_id'.input('screen_draw_id'))) {
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
}//end class
