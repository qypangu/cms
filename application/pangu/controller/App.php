<?php
namespace app\pangu\controller;

class App extends Auth{
    protected function init(){
        $this->model_name="rule";
    }
    public function index(){
        $this->redirect(url('lists',['menu_selected'=>input('menu_selected')]));
    }
    /**
     * |创建应用
     * @return \think\response\View
     */
    public function create(){
        return view();
    }
    
}
