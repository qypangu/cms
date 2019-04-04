<?php

namespace app\tool\controller;
use app\common\controller\AdminAuth;

class Gantt extends AdminAuth{
    protected function init(){
        $this->model_name='gantt';
    }
    /**
     * |明细信息
     * {@inheritDoc}
     * @see \app\common\controller\Pg::detail()
     */
    public function detail(){
        
        return view();
    }
    /**
     * |根据项目ID读取任务
     */
    public function getProject(){       
        $where['id']=input('project_id');
        $gantt=model('gantt')->where($where)->find();
        if ($gantt['content']=='') { //初始化项目
            $start_time=time()*1000;
            $data['tasks'][]=["id"=>1, "name"=>"请输入项目名称", "progress"=>0, "progressByWorklog"=>false, "relevance"=>0, "type"=>"", "typeId"=>"", "description"=>"", "code"=>"p01", "level"=>0, "status"=>"STATUS_ACTIVE", "depends"=>"", "canWrite"=>true, "start"=>$start_time, "duration"=>20, "end"=>$start_time+3600*24*5, "startIsMilestone"=>false, "endIsMilestone"=>false, "collapsed"=>false, "assigs"=>[], "hasChild"=>true];
            $data['selectedRow']='';
            $data['deletedTaskIds']=[];
        }else {
            $data=json_decode($gantt['content'],true);
        }        
        $resources=model('user')->where('status','=','1')->field(['id','nickname'=>'name'])->limit(500)->select();
        
        $roles=model('role')->where('status','=','1')->field(['id','title'=>'name'])->select();

        $data['resources']=$resources;
        $data['roles']=$roles;
        $data["canWrite"]=true;
        $data["canDelete"]=true;
        $data["canWriteOnParent"]=true;
        $data["canAdd"]=true;
        $this->success($data);
    }
    /**
     * |保存项目
     */
    public function saveProject(){
        $project_data['content']=input('project_data');
        if(input('id')>0){ //修改保存项目
            $where['id']=input('id');
            $rs=model('gantt')->save($project_data,$where);
        }else {//添加新的项目
            $rs=model('gantt')->save($project_data);
        }
        if ($rs) {
            $this->success("操作成功");
        }else {
            $this->error("操作失败");
        }
    }
    protected function object2array($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)$this->object2array($v);
            }
        }
        
        return $obj;
    }
}
