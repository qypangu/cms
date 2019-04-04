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
namespace app\screen\controller\admin;

use app\common\controller\AdminAuth;

class Draw extends AdminAuth{
    public function init(){
        $this->model_name="screen/ScreenDraw";
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
            $data = "http://{$_SERVER['HTTP_HOST']}".url('screen/draw/detail',['screen_draw_id '=>input('id')]);
        }
        $return = ['status'=>100,'info'=>'复制链接成功','data'=>$data];
        return json($return);
    }


}//end class
