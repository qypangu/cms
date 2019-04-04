<?php
namespace app\common\model;
use think\Model;
/**
 * ---------------------------------------------
 * @author 黄建文 2018-11-30
    name	模型名（默认为当前不含后缀的模型类名）
    table	数据表名（默认自动获取）
    pk	主键名（默认为id）
    connection	数据库连接（默认读取数据库配置）
    query	模型使用的查询类名称
    field	模型对应数据表的字段列表（数组）
 *
 */
class User extends Model{    
    //protected $table='pg_user';    // 设置当前模型对应的完整数据表名称
    //系统支持auto、insert和update三个属性
    protected $auto = [];
    protected $insert = ['create_time','password','roles','status' => 1]; 
    protected $update = ['roles','update_time'];  
    /**
     * 过虑条件设置
     */
    public function whereFields(){
        $where[]=['account','='];
        $where[]=['nickname','like'];
        $where[]=['mobile','in'];
        $where[]=['create_time','between'];
        $where[]=['status','='];
        return $where;
    }
    public function setPasswordAttr($value){
        return md5($value);
    }
    public function setUpdateTimeAttr() {
        return time();
    }
    public function setCreateTimeAttr() {
        return time();
    }
    
    public function setLastLoginIpAttr(){
        return request()->ip();
    }
    public function setRolesAttr($value){
        if ($value) {
            $roles=implode(',', $value);
            return $roles;
        }        
    }
    /**
     * |取得角色的属性
     */
    public function getRolesAttr($value){
        $roles=explode(",",$value);
        return $roles;
    }
    /**
     * |一个用户可以有多个角色
     * @return \think\model\relation\HasMany
     */
    public function roleUser(){
        return $this->hasMany('roleUser','uid');
    }
}
