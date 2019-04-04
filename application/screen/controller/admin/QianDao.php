<?php
namespace app\screen\controller\admin;

use app\common\controller\AdminAuth;

class QianDao extends AdminAuth {
    public function init()
    {
        $this->model_name="ScreenQiandao";
    }

    /*public function lists(){
        echo 123;
        exit;
        return view();
    }*/
    /**
    * 上传文件，主要针对异步上传的功能
    */
    public function uploadFile(){
        $rs=$this->upload();
        $this->result($rs,'200','上传成功');
    }

    //点击复制大屏链接
    public function copyLink(){
        //必须传过id
        if (!input('id')) {
            $return = ['status'=>400,'info'=>'必须传入参数过来'];
            return json($return);
            exit;
        }
        if (request()->isPost()) {
            $data = "http://{$_SERVER['HTTP_HOST']}".url('screen/index/index',['id'=>input('id')]);
        }
        $return = ['status'=>100,'info'=>'复制链接成功','data'=>$data];
        return json($return);
    }

}//end class