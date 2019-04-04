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
class RoleUser extends Model{    
    protected $table='pg_auth_role_user';    // 设置当前模型对应的完整数据表名称
    public $model_table='auth_role_user';  //此属性开放给db使用，对应一下数据表
    protected static function init(){
        
    }
}
