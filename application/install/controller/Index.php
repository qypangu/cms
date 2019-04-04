<?php
// +----------------------------------------------------------------------
// | WeiPHP [ 公众号和小程序运营管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.weiphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 凡星 <weiphp@weiphp.cn> <QQ:203163051>
// +----------------------------------------------------------------------
namespace app\install\controller;

use think\Controller;

class Index extends Controller
{
    // 安装首页
    public function index(){
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/install.lock')) {
           // $this->error('已经安装过，请删除uploads/install.lock再安装');
        }
        return view();
    }
    
    // 安装完成
    public function complete()
    {
        $step = session('step');
        
        if (! $step) {
            $this->redirect('index');
        } elseif ($step != 3) {
            $this->redirect("Install/step{$step}");
        }
        
        // 写入安装锁定文件
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/uploads/install.lock', 'lock');
        if (! session('update')) {
            // 创建配置文件
            $this->assign('info', session('config_file'));
        }

        session('step', null);
        session('error', null);
        session('update', null);
        session('pbid',null);
        return $this->fetch();
    }
}
