<?php
namespace app\pangu\controller;

class User extends Auth{
    protected function init(){
        $this->model_name="user";
        //查出角色
        $list_role=model('role')->select();
        foreach ($list_role as $role){
            $list_role_key[$role['id']]=$role;
        }
        $this->assign('list_role',$list_role_key);
    }
    public function index(){
        $this->redirect(url('lists',['menu_selected'=>input('menu_selected')]));
    }
    public function add(){
        if (request()->isPost()) {
            $model=model('user');
            $insert_id=$model->allowField(true)->save(input());            
            if ($insert_id!==false) { //保存成功;
                $roles=input('roles');
                foreach ($roles as $role_id){
                    $data_role_user[]=['role_id'=>$role_id];
                }
                $model->roleUser()->saveAll($data_role_user);  //关联新增
                $this->success(lang('新增成功!'));
            } else {
                $this->error(lang('新增失败!'));
            };
        }else {
            return view();
        }
    }
    public function edit() {
        if (request()->isPost()) {
            $model=model('user');
            $where['id']=input('id');
            $rs=$model->save(input(),$where);
            if ($rs!==false) {
                //删除对应的角色，重新添加
                $model->roleUser()->where('uid','=',$where['id'])->delete();
                //echo $model->getLastSql().'<br>';
                $roles=input('roles');
                foreach ($roles as $role_id){
                    $data_role_user[]=['role_id'=>$role_id];
                }
                $model->roleUser()->saveAll($data_role_user);
                //echo $model->getLastSql();
                $this->success(lang('编辑成功!'));
            } else {
                $this->error(lang('编辑失败!'));
            }
        }else {
            $model=model('user');
            $id=input('id');
            $info = $model->getById($id);
            $this->assign('info',$info);
            return view();
        };
    }
    /**
     * |用户设置
     * @return \think\response\View
     */
    public function setting(){
        if (request()->isPost()) {
            $this->update();
        }else{
            $where['id']=session('auth_user_id');
            $info=model('user')->where($where)->find();
            $this->assign('info',$info);
            return view();
        }		
    }
    /**
     * |修改密码
     */
    public function editPassword(){
        if (request()->isPost()) {
            $password=input('password');
            if (strlen($password)>30 || strlen($password)<6){
                $this->error("密码必须为6-30位的字符串");
            }
            if(!preg_match("/^[a-z\d]*$/i",$password)){
                $this->error("密码只能包含数字和字母");
            }
            
            $where[]=['id','=',session('auth_user_id')];
            $info=model('user')->where($where)->find();
            if($info['password']!=md5(input('old_password'))){
                $this->error("操作失败，旧密码不正确！");
            }else {
                $data['password']=md5($password);
                $rs=model('user')->where($where)->update($data);
                if ($rs) {
                    $this->success("密码修改成功！");
                }else {
                    $this->error("操作失败！");
                }
            }
            
        }else{
            return view();
        }	
    }    
    
}
