<?php
namespace app\pangu\controller;
use think\Console;
class Model extends Auth{
    protected function init(){
        $this->model_name="models";
    }
    public function index(){
        $this->redirect(url('lists',['menu_selected'=>input('menu_selected')]));
    }
    /**
     * |添加模型
     * {@inheritDoc}
     * @see \app\pangu\controller\Auth::add()
     */
    public function add(){
        if (request()->isPost()) {
            $module=input('module');
            $model_name=ucfirst(input('model_name'));
            Console::call('make:model', [$module.'/'.$model_name]);
            $this->insert();
        }else {
            $list_resource=scandir("../application");
            foreach ($list_resource as $resource){
                if (is_dir('../application/'.$resource)&&!($resource=='.'||$resource=='..')) {
                    $list_module[]=$resource;
                }                
            }
            $this->assign('list_module',$list_module);
            return view();
        }
    }
}
