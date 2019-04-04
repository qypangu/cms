<?php

namespace app\common\model;

use think\Model;

class WeixinUser extends Model{
    protected $table='pg_weixin_user';    // 设置当前模型对应的完整数据表名称
    public $model_table='weixin_user';  //此属性开放给db使用，对应一下数据表
    
    protected $auto = [];
    protected $insert = ['privilege','create_time','status' => 1];
    protected $update = ['update_time'];
    
    public function setUpdateTimeAttr() {
        return time();
    }
    public function setCreateTimeAttr() {
        return time();
    }
    public function setPrivilegeAttr($value){
        if ($value) {
            $privilege=json_encode($value);
            return $privilege;
        }else{
            return '';
        }
    }
    /**
     * |取得角色的属性
     */
    public function getPrivilegeAttr($value){
        $privilege=json_decode($value,true);
        return $privilege;
    }
}
