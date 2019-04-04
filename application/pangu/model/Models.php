<?php
namespace app\pangu\model;
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
class Models extends Model{    
    protected $table='pg_model';    // 设置当前模型对应的完整数据表名称
    public $model_table='model';
    protected $insert = ['create_time','create_user_id'=>'','status' => 1];
    protected $update = ['update_time'];  
    protected static function init(){
    }
    public function setCreateUserIdAttr(){
        return session('auth_user_id');
    }
    public function setUpdateTimeAttr() {
        return time();
    }
    public function setCreateTimeAttr() {
        return time();
    }
}
