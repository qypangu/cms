<?php
namespace app\pangu\model;
use think\Model;
/**
 * 关联模型
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
class RelationModel extends Model{
    public $relation_model;//关联对像模型
}
