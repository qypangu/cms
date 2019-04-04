<?php
namespace app\screen\controller;

use app\common\controller\Web;

class Publics extends Web {

    public function initialize()
    {
        //判断大屏是否登录
        if ((session('screen_id'.input('id')))) {
            $this->assign('login_ident',1);
        } else {
            $this->assign('login_ident',0);
        }

        if ((session('screen_draw_id'.input('screen_draw_id')))) {
            $this->assign('login_draw_ident',1);
        } else {
            $this->assign('login_draw_ident',0);
        }
    }
}//end class