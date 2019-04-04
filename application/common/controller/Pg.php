<?php
namespace app\common\controller;
use think\Controller;
use app\pangu\model\RelationModel;
use \phpoffice\phpexcel;

class Pg extends Controller{
    protected $model_name;
    protected function initialize(){
        $this->model_name=$this->request->controller();
        $this->init();
    }
    protected function init(){
        
    }
    public function index(){
        return view();
    }
    /**
     * |列表
     */
    public function lists(){
        $where=$this->_search();
        $this->_lists($where);
        return view();
    }
    /**
     * |查询条件,返回如下格式
     * |$where[]=['id','=|>|<|!=','1']
     * |$where[]=['id','in',[1,2,3]]
     * |$where[]=['name','like','%pang%']
     * |$where[]=['create_time','between ',['123','456']]
     * @return array $where
     */
    protected function _search() {
        $model=model($this->model_name);
        $where='';
        if (method_exists($model,'whereFields')) { //模型中有定义查询条件就去根据模型的规则查询
            $field_arr=$model->whereFields();
            foreach ($field_arr as $field){
                if (is_array(input($field[0]))) {
                    if ($field[1]=='between') {  //多数用于时间范围和数值
                        $between_value=input($field[0]);
                        $start_time=is_numeric($between_value[0])?$between_value[0]:strtotime($between_value[0]);
                        $end_time=is_numeric($between_value[1])?$between_value[1]:strtotime($between_value[1]);
                        $where[]=[$field[0],$field[1],[$start_time,$end_time]];
                    }elseif (count(array_filter(input($field[0])))>0){
                        $where[]=[$field[0],$field[1],array_filter(input($field[0]))];
                    }
                }elseif (input($field[0])!=''){
                    if ($field[1]=='like') {
                        $where[]=[$field[0],$field[1],'%'.input($field[0]).'%'];
                    }else {
                        $where[]=[$field[0],$field[1],input($field[0])];
                    }                    
                }elseif ($field[0]=='status'){
                    $where[]=['status','>=','0'];
                }
            }
        }else { //没有定义就直接去数据库中查询对应条件的数据,此操作有风险,建议废除
            $field_arr=db($model->model_table)->getTableFields();
            foreach ($field_arr as $field) {
                if (input($field)!='') {
                    $where[]=[$field,'=',input($field)];
                }elseif ($field=='status'){
                    $where[]=['status','>=','0'];
                }
            }
        } 
        //自定义的过滤条件
        $filter=$this->request->action().'Filter';
        if (method_exists($this,$filter)) {
            $where=$this->$filter($where);
        }
        return $where;
    }    
    protected function _lists($where){
        $model=model($this->model_name);
        $page_config['query']=[];
        //组织传递的URL参数
        if (is_array($where)) {
            foreach ($where as $field){
                if ($field[0]=='status') {
                    continue;
                }
                $page_config['query'][$field[0]]=$field[2];
            }
        }
        if (input('order_by')) {
            $order_by=input('order_by');
        }else {
            $pk=$model->getPk();
            $order_by="{$pk} desc";
        }
        
        $list=$model->where($where)->order($order_by)->paginate(config('page_size'),'',$page_config);
        $this->assign('list',$list);
    }
    /**
     * 添加数据
     *
     */
    public function add(){
        if (request()->isPost()) {
            $this->insert();
        }else {
            return view();
        }
    }    
    protected function insert() {
        //$this->uploadFile();
        $model=model($this->model_name);
        $insert_id=$model->allowField(true)->save(input());
        if ($insert_id!==false) { //保存成功;
            $this->success(lang('新增成功!'));
        } else {
            $this->error(lang('新增失败!'));
        }
    }
    
    /**
     * |修改操作
     */
    public function edit(){
        if (request()->isPost()) {
            $this->update();
        }else {
            $model=model($this->model_name);
            $id=input($model->getPk());
            $pk='getBy'.$model->getPk();
            $info = $model->{$pk}($id);//var_export($info);exit;
            $this->assign('info',$info);
            return view();
        }
        
        
    }
    protected function update(){        
        //$this->uploadFile();
        $model=model($this->model_name);
        $id=$model->getPk();
        $where[$id]=input($id);
        $rs=$model->save(input(),$where);
        if ($rs!==false) {
            $this->success(lang('编辑成功!'));
        } else {
            $this->error(lang('编辑失败!'));
        }
    }
    /**
     * |删除操作
     */
    public function delete(){
        $this->_delete();
    }
    protected function _delete() {
        $model=model($this->model_name);
        if (!empty($model)) {
            $pk=$model->getPk();
            $id=input($pk);
            if(isset($id)) {
                $where[]=[$pk,'in',$id];
                $rs=$model->where($where)->setField('status',-1);
                if($rs!==false) {
                    $this->success(lang("数据已丢进垃圾桶"));
                }else{
                    $this->error(lang("操作失败"));
                }
            } else {
                $this->error(lang('非法操作'));
            }
        }
    }
    /**
     * |永久删除
     */
    public function deleteForever(){
        $model=model($this->model_name);
        $pk=$model->getPk();
        $id=input($pk);
        $rs=$model->destroy($id);
        if ($rs){
            $this->success(lang('记录已永久删除'));
        }else {
            $this->error(lang('操作失败'));
        }
    }
    /**
     * |显示详细信息
     *
     */
    public function detail(){
        $this->_detail();
        return view();
    }    
    protected function _detail(){
        $model=model($this->model_name);
        $where=$this->_search();
        if ($model instanceof RelationModel) {
            $info=$model->where($where)->with($model->relation_model)->find();
        }else {
            $info=$model->where($where)->find();
        }
        $this->assign('info', $info);        
    }
    /**
     * |导出数据
     */
    public function export(){
        //此功能日后再实现
    }
    /**
     * |上传数据
     */
    public function uploadFile() {
        $this->result("",'100','文件上传必需要异步，并且为了安全性问题，此方法必需重写,如$rs=$this->upload();$this->result($rs);');
    }
    protected function upload(){
        $file_array=[];
        $list_file=request()->file('upload_file');
        foreach ($list_file as $key=>$file){
            // 移动到框架应用根目录/uploads/ 目录下
            $info=$file->validate(['size'=>15678000,'ext'=>'jpg,png,gif,xls,xlsx,mp3,mp4,wav,'])->move($_SERVER['DOCUMENT_ROOT'].'/uploads/');
            if($info){
                $file_array[$key]['file_name']=$info->getFilename();
                $file_array[$key]['file_path']='/uploads/'.$info->getSaveName();
                $file_array[$key]['ext']=$info->getExtension();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        return $file_array;
    }
    /**
     * |导入数据
     */
    public function importData(){
        $excel_file='/uploads/demo.xlsx';
        $field_key=['姓名'=>'user_name','手机号'=>'mobile','座席'=>'number_group'];
        $list_excel_data=$this->import($excel_file,$field_key);
        $rs=$model->saveAll($list_excel_data);
        if ($rs){
            $this->success(lang("数据导入成功"));
        }else {
            $this->error(lang("数据导入失败"));
        }
    }
    //$excel_file='/uploads/20181225/demo.xls';
    protected function import($excel_file,$field_key=[]){
        $row_num=0;
        $objPHPExcel =\PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT'].$excel_file);
        foreach ($objPHPExcel->getActiveSheet()->getRowIterator() as $row){
            $info=[];
            $row_num++;//行数
            $cellIterator=$row->getCellIterator();
            if ($row_num==1){//第一行设置对应的字段
                foreach ($cellIterator as $cell) {
                    $cell_value=trim($cell->getValue());
                    if ($cell_value){
                        $key_arr[]=$field_key[$cell_value];
                    }
                }
            }else {//第二行开始写数据入数据库
                $cell_num=0;//列数，从第0列开始
                foreach ($cellIterator as $cell) {
                    $cell_value=trim($cell->getValue());
                    if (strlen($cell_value)<1) { //空值不再赋值
                        continue;
                    }
                    if (substr($cell_value,0,1)=="'") $cell_value=substr($cell_value,1); //去掉第一个 '
                    //判断 是否是日期格式
                    /*if ($cell->getDataType()==\PHPExcel_Cell_DataType::TYPE_NUMERIC){
                        $cellstyleformat=$cell->getParent()->getStyle( $cell->getCoordinate() )->getNumberFormat();
                        $formatcode=$cellstyleformat->getFormatCode();
                        if (preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i', $formatcode)) {
                            $cell_value=date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($cell_value));
                        }
                    }*/
                    $info[$key_arr[$cell_num]]=$cell_value;
                    $cell_num=$cell_num+1;
                }
                if ($info[$key_arr[0]]==''){//第0列为空要退出
                    break;
                }
                /*
                $action_method=($_REQUEST['_URL_'][2]==''||$_REQUEST['_URL_'][2]=='index')?'index':$_REQUEST['_URL_'][2];
                $_man_method='_man_'.$action_method;
                if (method_exists($this,$_man_method)) {
                    $info=$this->$_man_method($info,$BatchOperation);
                }*/
                //当没有任何数据时不添加进去
                if (is_array($info)){
                    $list_excel_data[]=$info; //下一步处理要每1000条数据才写入一次数据库
                    //echo '<br>=========<br>';
                    //var_export($info);
                    //$info['create_user_id']=$_SESSION[C('USER_AUTH_KEY')];
                    //$info['create_time']=time();
                }
            }
        }
        //$rs=$model->saveAll($insert_list);
        return $list_excel_data;
    }
    
}
