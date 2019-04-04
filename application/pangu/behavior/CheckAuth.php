<?php
namespace app\pangu\behavior;

class CheckAuth{
    public function run($param){
        if (session('auth_user_id')) {
            echo "<div>用户已授权的认证登录</div>";
        }else {
            echo "<div style='color:red'>用户未授权，要跳转到上一页</div>";
            redirect()->restore();            
        }
        /*
        session('auth_user_id','5');
        
        $menu_tree=model('authRule')->getMenuTree();
        $this->assign('menu_tree',$menu_tree);
        session('account','黄建文');
        session('roles','系统管理员');
        */
    }
}
