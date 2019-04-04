<?php

namespace app\screen\model;

use think\Model;

class ScreenDraw extends Model{
    public $model_table='ScreenDraw';  //此属性开放给db使用，对应数据表，不用户前缀,如数据表是pg_user_group，就填写UserGroup

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
    /**
     * |取得抽奖信息
     */
    public function getLuck($screen_id,$award_level=1,$get_lucky_count=1){
        //先抽出内定的中奖用户
        $where_log_plan[]=['status','=','1'];
        $where_log_plan[]=['award_level','=','0']; //0为未抽中的用户
        $where_log_plan[]=['award_level_plan','=',$award_level];
        $where_log_plan[]=['screen_id','=',$screen_id]; //0为未抽中的用户
        $list_user_plan=db('screenDrawLog')->where($where_log_plan)->select();
        $list_user_plan_count=count($list_user_plan);
        if ($list_user_plan_count>0&&$list_user_plan_count>=$get_lucky_count) { //只有内定用户时
            $list_user_plan=array_slice($list_user_plan,0,$get_lucky_count);
            return $list_user_plan;
        }elseif ($list_user_plan_count>0&&$list_user_plan_count<$get_lucky_count){ //有部分内定用户时，先取内定用户
            $list_user_plan=array_slice($list_user_plan,0,$list_user_plan_count);
            $get_lucky_count=$get_lucky_count-$list_user_plan_count;
            //再抽随机的
            $where_log[]=['status','=','1'];
            $where_log[]=['award_level','=','0']; //0为未抽中的用户
            $where_log[]=['screen_id','=',$screen_id]; //0为未抽中的用户
            $list_user=db('screenDrawLog')->where($where_log)->select();
            shuffle($list_user);
            $list_lucky=array_slice($list_user,0,$get_lucky_count);
            if (isset($list_user_plan)) {
                $list_lucky=array_merge($list_lucky,$list_user_plan);
            }
            return $list_lucky;
        }else { //没有内定用户时随机抽取
            $where_log[]=['status','=','1'];
            $where_log[]=['award_level','=','0']; //0为未抽中的用户
            $where_log[]=['screen_id','=',$screen_id]; //0为未抽中的用户
            $list_user=db('screenDrawLog')->where($where_log)->select();
            shuffle($list_user);
            $list_lucky=array_slice($list_user,0,$get_lucky_count);
            return $list_lucky;
        }
    }
    /**
     * |标记为已中奖状态
     */
    public function setLuck($luck_user,$award_level=1){
        foreach ($luck_user as $user){
            $log_id[]=$user['id'];
        }
        $where[]=['id','in',$log_id];
        $data['award_level']=$award_level;
        $rs=db('screenDrawLog')->where($where)->update($data);
        return $rs;
    }
}//end class