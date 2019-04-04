<?php
namespace app\common\controller;

class AdminAuth extends Pg{
    protected $model_name;
    protected function initialize(){
        $this->model_name=$this->request->controller();
        if (!session('?auth_user_id')) {
            $this->redirect('pangu/Publics/login');exit;
        }
        //根据session('auth_user_id')去取得对应的权限
        $menu_tree=model('rule')->getMenuTree();        
        $this->assign('menu_tree',$menu_tree);
        $this->init();
    }
    protected function init(){
        
    }
}
