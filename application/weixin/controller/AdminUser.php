<?php

namespace app\weixin\controller;

use app\common\controller\AdminAuth;
use think\weixin\Wechat;
class AdminUser extends AdminAuth{
    protected function init(){
        $this->model_name='weixinUser';
    }
}
