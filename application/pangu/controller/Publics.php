<?php
namespace app\pangu\controller;
use think\Controller;

class Publics extends Controller{
    /**
     * |用户登录
     * @return \think\response\View
     */
    public function login(){
        if (request()->isPost()) {
            $where['account']=input('account');
            $where['password']=md5(input('password'));
            $where['status']=1;
            $user=model('user')->where($where)->find();
            if ($user) {
                session('auth_user_id',$user['id']);
                cookie('account',$user['account']);
                cookie('nickname',$user['nickname']);
                $url=url('pangu/Index/index');
                $this->success(lang('欢迎进入').config('app_name'),$url);
            }else {
                $this->error(lang("用户名或密码错误"));
            }
        }else{
            return view();
        }		
    }
    /**
     * |用户登录
     */
    public function logout(){
        session(null);
        cookie(null);
        $url=url('login');
        $this->redirect($url);
    }
    /**
     * |注册用户
     */
    public function register(){
        return view();
    }
}
