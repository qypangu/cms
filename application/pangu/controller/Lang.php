<?php
namespace app\pangu\controller;
use think\Controller;

class Lang extends Auth{
    protected function init(){
        $this->model_name='role';        
    }
    /**
     * |语言包
     * @return \think\response\View
     */
    public function index(){
		$this->redirect(url('lists',['menu_selected'=>input('menu_selected')]));
    }
    public function lists(){
        $sql="show tables";
        $list_table=\think\Db::query($sql);
        foreach ($list_table as $table){
            foreach ($table as $t){
                $table_arr[]=$t;
            }
        }
        $this->assign('list_table',$table_arr);
        $list_module=$this->scanModule();
        $this->assign('list_module',$list_module);
        $list=$this->scanLang();
        $this->assign('list',$list);
        return view();
    }
    /**
     * 添加语言包
     * {@inheritDoc}
     * @see \app\common\controller\Pg::add()
     */
    public function add(){
        if (request()->isPost()) {
            $module_controller=input('module');
            $module_arr=explode('/', $module_controller);
            $module=$module_arr[0];
            $controller=$module_arr[1];
            $lang_file=input('lang');            
            if ($module=='lang') {
                $file="../application/lang/{$lang_file}.php";
                if (file_exists($file)) {
                    $lang_str=file_get_contents($file);
                }else {
                    $lang_str="return [];";
                }
            }else {
                $dir="../application/{$module}/lang";
                if (!file_exists($dir)) {
                    mkdir ($dir,0777,true);
                }
                $file="{$dir}/{$lang_file}.php";
                if (file_exists($file)) {
                    $lang_str=file_get_contents($file);
                }else {
                    $lang_str="return [];";
                }       
            }
            $lang_str=str_replace('<?php', '', $lang_str);
            $list_lang=eval($lang_str);
            //读出数据库的信息
            $table_name=input('table_name');
            $lang_table=str_replace(config('database.prefix'),'',$table_name);
            if ($table_name) {//通过对数据表进行创建语言包
                $sql="show full columns from $table_name";
                $list_field=\think\Db::query($sql);
                foreach ($list_field as $field_desc){
                    $lang_arr["{$lang_table}:{$field_desc['Field']}"]=$field_desc['Comment'];
                }
            }
            $lang_all=array_merge($list_lang,$lang_arr);
            $save_lang_str="<?php return ".var_export($lang_all,true).';';
            if ($module=='lang') {
                $rs=file_put_contents("../application/lang/{$lang_file}.php",$save_lang_str);
            }else {
                $rs=file_put_contents("../application/{$module}/lang/{$lang_file}.php",$save_lang_str);
            }
            if ($rs) {
                $this->success("添加成功");
            }else {
                $this->error("添加失败");
            }
        }else{
            return view();
        }
    }
    /**
     * 编辑语言包
     * {@inheritDoc}
     * @see \app\common\controller\Pg::edit()
     */
    public function edit(){
        if (request()->isPost()) {
            $module=input('module');
            $lang_file=input('lang');
            $new_lang=input('new_lang');            
            foreach ($new_lang as $lang){
                $lang_arr[$lang['key']]=$lang['value'];
            }
            $list_lang=input('list_lang');
            $lang_all=array_merge($list_lang,$lang_arr);
            $save_lang_str="<?php return ".var_export($lang_all,true).';';
            if ($module=='lang') {
                $rs=file_put_contents("../application/lang/{$lang_file}.php",$save_lang_str);
            }else {
                $rs=file_put_contents("../application/{$module}/lang/{$lang_file}.php",$save_lang_str);
            }
            if ($rs) {
                $this->success("更新成功");
            }else {
                $this->error("更新失败");
            }
        }else {
            $module=input('module');
            $lang=input('lang');
            if ($module=='lang') {
                $lang_str=file_get_contents("../application/lang/{$lang}.php");
            }else {
                $lang_str=file_get_contents("../application/{$module}/lang/{$lang}.php");
            }
            $lang_str=str_replace('<?php', '', $lang_str);
            $list_lang=eval($lang_str);
            $this->assign('list_lang',$list_lang);
            return view();
        }        
    }
    /**
     * 扫描应用目录下的语言包
     * @param unknown $dir
     */
    private function scanLang(){
        $application_lang=scandir('../application/lang');
        foreach ($application_lang as $lang){
            if($lang!='.'&&$lang!='..'&&is_file('../application/lang/'.$lang)){
                $lang=substr($lang, 0,-4);
                $list_lang[]=['module'=>'lang','lang'=>$lang];
            }
        }
        $list_module=scandir('../application/');
        foreach ($list_module as $module){
            if ($module!='.'&&$module!='..'&&$module!='lang'&&is_dir("../application/{$module}/lang")) {
                $module_lang=scandir("../application/{$module}/lang");
                foreach ($module_lang as $lang){
                    if($lang!='.'&&$lang!='..'&&is_file("../application/{$module}/lang/{$lang}")){
                        $lang=substr($lang, 0,-4);
                        $list_lang[]=['module'=>$module,'lang'=>$lang];
                    }
                }
            }
        }//end foreach ($list_module as $module){
        return $list_lang;
    }
    /**
     * |扫描模块
     */
    private function scanModule(){
        $application_module=scandir('../application');
        foreach ($application_module as $module){
            if($module!='.'&&$module!='..'&&$module!='lang'&&is_dir('../application/'.$module)){
               // $module_arr[]=['title'=>$module,'level'=>'1'];
                $application_module_controller=scandir('../application/'.$module.'/controller');
                foreach ($application_module_controller as $controller){
                    if(is_file('../application/'.$module.'/controller/'.$controller)){
                        $controller=substr($controller, 0,-4);
                        $module_arr[]=['title'=>"{$module}/{$controller}",'level'=>'2'];                        
                    }
                }
            }
        }
        return $module_arr;
    }
}
