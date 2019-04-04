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

class DrawLog extends AdminAuth{
    public function init(){
        $this->model_name="screen/ScreenDrawLog";
    }

    protected $beforeActionList=[
        'beforeEdit'=>['only'=>'edit,add,lists'],
    ];
    protected function beforeEdit(){
        //大屏名称
        $this->assign('screen_draw',screen_draw());
        return view();
    }
}//end class
