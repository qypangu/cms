<?php

namespace app\weixin\controller;

use app\common\controller\AdminAuth;
use think\weixin\Wechat;
class AdminMenu extends AdminAuth{
    protected function init(){
        $this->model_name='weixinConfig';
    }
    /**
     * |菜单设置
     * {@inheritDoc}
     * @see \app\common\controller\Pg::edit()
     */
    public function edit(){
        if (input('id')) {
            $where[]=['id','=',input('id')];            
        }
        $where[]=['status','=',1];
        $where[]=['create_user_id','=',session('auth_user_id')];
        
        $options=model("weixinConfig")->field(['id','public_name','public_id','token','encodingaeskey','appid','secret'=>'appsecret'])->where($where)->order('id desc')->find();
        $this->assign('info',$options);
        $weObj=new Wechat($options);
        
        if (request()->isPost()) {
            $list_menu=input('menu');
            foreach ($list_menu as $key=>$menu){
                if (trim($menu['name'])=='') {
                    unset($list_menu[$key]);
                    continue;
                }
                if (isset($menu['sub_button'])) {
                    foreach ($menu['sub_button'] as $k=>$button){
                        if (trim($button['name'])=='') {
                            unset($list_menu[$key]['sub_button'][$k]);
                            continue;
                        }
                    }
                }
            }
            $new_menu['button']=$list_menu;
            $result=$weObj->createMenu($new_menu);
            if ($result) {
                $this->success('操作成功');
            }else {
                $this->error($weObj->errCode.':'.$weObj->errMsg);
            }
        }else {
            $list_menu=$weObj->getMenu();
            $this->assign('list_menu',$list_menu['menu']['button']);
            return view();
        }        
    }
}
