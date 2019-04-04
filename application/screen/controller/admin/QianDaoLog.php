<?php
namespace app\screen\controller\admin;

use app\common\controller\AdminAuth;

class QianDaoLog extends AdminAuth {
    public function init()
    {
        $this->model_name="ScreenQiandaoLog";
    }

    protected $beforeActionList=[
        'beforeEdit'=>['only'=>'edit,add,lists'],
    ];
    protected function beforeEdit(){
        //大屏名称
        $this->assign('screen_qiandao',screen_qiandao());
        return view();
    }

}//end class