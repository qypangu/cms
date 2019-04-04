<?php

namespace app\common\model;

use app\pangu\model\RelationModel;

class Article extends RelationModel{
    public $model_table='Article';  //此属性开放给db使用，对应数据表，不用户前缀,如数据表是pg_user_group，就填写UserGroup
    public $relation_model=['articleContent'];//定义关联模型

    protected $insert = ['create_user_id','create_user_name','create_time','status' => 1];
    protected $update = ['update_time'];
    //以下3个默认必须有的
    public function setCreateUserIdAttr(){
        return session('auth_user_id');
    }
    public function setCreateUserNameAttr(){
        return cookie('nickname');
    }
    public function setCreateTimeAttr() {
        return time();
    }
    public function setUpdateTimeAttr() {
        return time();
    }
    /**
     * |读取数据库对应的信息，后续要思考废除这个功能
     */
    public function getFieldsConfig(){
        $table_fields=db($this->model_table)->getFieldsType();
        return $table_fields;
    }
    public function articleContent(){
        return $this->hasOne('articleData')->bind('content');
    }
}//end class