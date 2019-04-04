<?php

namespace app\weixin\controller;
use app\common\controller\AdminAuth;

class AdminApi extends AdminAuth{
    public function init(){
        $this->model_name="weixinConfig";
    }
    
}
