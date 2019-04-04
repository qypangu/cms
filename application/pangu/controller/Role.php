<?php
namespace app\pangu\controller;
use think\Controller;

class Role extends Auth{
    //前置操作
    protected $beforeActionList = [
        'getRuleList' =>  ['only'=>'edit,add'],
    ];
    protected function init(){
        $this->model_name='role';
        
    }
    /**
     * |角色管理列表
     * @return \think\response\View
     */
    public function index(){
		$this->redirect(url('lists',['menu_selected'=>input('menu_selected')]));
    }
    /**
     * |取得权限信息列表
     */
    protected function getRuleList(){
        if (!request()->isPost()) {
            $where['status']=1;
            $rule_tree=model('rule')->field(['list_sort'=>'id','id'=>'true_id','pid','title'=>'name'])->order("list_sort ASC")->where($where)->select()->toArray();
            $this->assign('rule_tree',$rule_tree);
        }
    }
    /**
     * 整理菜单排序的
     */
    public function test(){
        $where_level[]=['level','IN',[2,3]];
        $list=model('rule')->where($where_level)->order('id asc')->select();
        echo model('rule')->getLastSql().'<br>==============<br>';
        foreach ($list  as $val){
            $where='';
            $p_list_sort=model('rule')->where('id',$val['pid'])->value('list_sort');
            if ($p_list_sort>0) {
                $new_val['pid']=$p_list_sort;
                $where[]=['id','=',$val['id']];
                db('auth_rule')->where($where)->update($new_val);
                echo model('rule')->getLastSql().'<br>';
            }else {
                echo model('rule')->getLastSql().'<br>======跳过=====<br>';
            }
            
        }
    }
}
