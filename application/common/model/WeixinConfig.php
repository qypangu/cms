<?php

namespace app\common\model;

use think\Model;

class WeixinConfig extends Model
{
    //
    public $model_table='weixinConfig';
    /**
     * |设置默认查询字段 ,此功能未测试
     * @param unknown $query
     * @param unknown $value
     * @param unknown $data
     */
    public function searchCreateUserIdAttr($query,$value,$data){
        if ($value) {
            $query->where('create_user_id','=',$value);
        }else{
            echo session('auth_user_id');exit;
            $query->where('create_user_id','=',session('auth_user_id'));
        }       
    }
}
