<?php
namespace app\pangu\controller;
use think\Console;
use think\facade\Request;
use think\facade\Url;
use think\Loader;
class Rule extends Auth {
    protected $beforeActionList=[
        'beforeEdit'=>['only'=>'edit,add'],
    ];
    protected function init(){
        $this->model_name='rule';
    }
	/**
	 * 权限列表
	 */
	public function index(){
	    $this->redirect(url('lists',['menu_selected'=>input('menu_selected')]));
	}	
	public function lists(){
	    $where=$this->_search('authRule');
	    $where[]=['status','=','1'];
	    $list=model('rule')->where($where)->order("list_sort ASC ")->select();
	    $this->assign('list',$list);
	    return view();
	}
	protected function beforeEdit(){
	    $where[]=['status','>=','0'];
	    $list_rule=model('rule')->order("list_sort ASC")->where($where)->select();
	    $this->assign('list_rule',$list_rule);
	    //对应模型
	    $where_model[]=['status','>','0'];
	    $list_model=model('models')->order("id DESC")->where($where_model)->select();
	    $this->assign('list_model',$list_model);
	    
	}
	/**
	 * |添加规则
	 * {@inheritDoc}
	 * @see \app\pangu\controller\Auth::add()
	 */
	public function add(){
	    if (request()->isPost()) {	        
	        if (input('create_file')==1) { //创建文件和目录 
	            if (input('level')==1) { //生成模块目录结构
	                $module=input('name');
	                $name_arr=explode('/', $module);
	                $name_arr[0]=lcfirst($name_arr[0]); //模块要小写
	                if (!isset($name_arr[1])||!isset($name_arr[2])) {
	                    request()->name="{$name_arr[0]}/Index/index";
	                }
	                
	                Console::call('build', ["--module",$name_arr[0]]);
	                //当创建的是管理的后台时
	                if(strpos($name_arr[1], '.')){
    	                $admin_folder=str_replace('.','/',$name_arr[1]);
    	                Console::call('make:controller',["{$name_arr[0]}/{$admin_folder}"]);
    	                $this->indexReplate();
	                }
	                $this->indexReplate();
	                //替换model_name的内容
	                $index_content=file_get_contents("../application/{$name_arr[0]}/controller/Index.php");
	                $search_arr=["{%model_name%}"];
	                $replace_arr=[input('model_name')];
	                $index_content=str_replace($search_arr,$replace_arr, $index_content);
	                file_put_contents("../application/{$name_arr[0]}/controller/Index.php", $index_content);
	            }elseif (input('level')==2){ //生成控制器,这个要想办法生成自己定义的控制器模板
	                $controller=input('name');
	                if (strpos($controller, '.')) { //有管理后台时
	                    $name_arr=explode('/', $controller);
	                    $name_arr[0]=lcfirst($name_arr[0]); //模块要小写
	                    $admin_controller=explode('.', $name_arr[1]);
	                    $name_arr[1]=ucfirst($admin_controller[1]); //控制器必需首字母大写
	                    if (!isset($name_arr[2])) {
	                        request()->name="{$name_arr[0]}/{$admin_controller[0]}.{$name_arr[1]}/lists";
	                    }
	                    Console::call('make:controller',["{$name_arr[0]}/{$admin_controller[0]}/{$name_arr[1]}"]);
	                    //创建列表类型的HTML文件
	                    $this->listsReplace();
	                    //当控制器与模型名称不一样时要替换
	                    $model_name=input('model_name');
	                    if ($name_arr[1]!==$model_name) {
	                        $content=file_get_contents("../application/{$name_arr[0]}/controller/{$admin_controller[0]}/{$name_arr[1]}.php");
	                        $search_arr=['$this->model_name="'.$name_arr[1].'"'];
	                        $replace_arr=['$this->model_name="'.input('model_name').'"'];
	                        $content=str_replace($search_arr,$replace_arr, $content);
	                        file_put_contents("../application/{$name_arr[0]}/controller/{$admin_controller[0]}/{$name_arr[1]}.php", $content);
	                    }
	                }else {
    	                $name_arr=explode('/', $controller);
    	                $name_arr[0]=lcfirst($name_arr[0]); //模块要小写
    	                $name_arr[1]=ucfirst($name_arr[1]); //控制器必需首字母大写
    	                if (!isset($name_arr[2])) {
    	                    request()->name="{$name_arr[0]}/{$name_arr[1]}/lists";
    	                }
    	                Console::call('make:controller',["{$name_arr[0]}/{$name_arr[1]}"]);
    	                //创建列表类型的HTML文件
    	                $this->listsReplace();
    	                //当控制器与模型名称不一样时要替换
    	                $model_name=input('model_name');
    	                if ($name_arr[1]!==$model_name) {
    	                    $content=file_get_contents("../application/{$name_arr[0]}/controller/{$name_arr[1]}.php");
    	                    $search_arr=['$this->model_name="'.$name_arr[1].'"'];
    	                    $replace_arr=['$this->model_name="'.input('model_name').'"'];
    	                    $content=str_replace($search_arr,$replace_arr, $content);
    	                    file_put_contents("../application/{$name_arr[0]}/controller/{$name_arr[1]}.php", $content);
    	                }
	                }
	            }elseif (input('level')==3){ //找到对应控制器生成操作
	                $demo_html_file=input('demo_html');
	                if (stripos($demo_html_file,'index')) {
	                    $this->indexReplace();
	                }elseif (stripos($demo_html_file,'lists')){
	                    $this->listsReplace();
	                }elseif (stripos($demo_html_file,'add')){	                    
	                    $this->addReplace();
	                }elseif (stripos($demo_html_file,'edit')){
	                    $this->editReplace();
	                }//end if (stripos($demo_html_file,'index'))
	                
	            }
	        }
	        $data=input();
	        if (strpos($data['name'], '.')) {
	            $name_arr=explode('.',$data['name']);
	            $admin_name=$name_arr[0].'.'.lcfirst($name_arr[1]);	            
	            $data['name']=$admin_name;
	        }
	        $this->insert($data);
	    }else{	        
	        return view();
	    }
	}
	/**
	 * |替换首页的HTML
	 */
	protected function indexReplate(){
	    $demo_html_file=input('demo_html');
	    $html_index='';
	    $search_arr=['{%html_index%}'];
	    $replace_arr=[$html_index];
	    $this->createHtml($demo_html_file,input('name'),$search_arr,$replace_arr);
	}
	/**
	 * |替换列表的HTML
	 */
	protected function listsReplace(){
	    //查出数据表对应的数据字段
	    $model_name=input('model_name');
	    $fields_config=model($model_name)->getFieldsConfig();
	    unset($fields_config['id']);
	    unset($fields_config['create_user_id']);
	    unset($fields_config['create_user_name']);
	    unset($fields_config['create_time']);
	    unset($fields_config['update_time']);
	    //此操作必定要生成HTML
	    $demo_html_file=input('demo_html');
	    $html_form_search='';
	    $html_table_thead_th='';
	    $html_table_tr_td='';	    
	    foreach ($fields_config as $fields=>$type){
	        preg_match("/\d+/",$type,$int_arr);
	        //echo $fields.'=='.var_export($int_arr,true).'<br><BR>';
	        if (stripos($fields, 'img')) { //当字段是图片标记时
	            $html_table_thead_th.=<<<EOT
<th>{:lang('{$model_name}:{$fields}')}</th>\n
EOT;
	            $html_table_tr_td.=<<<EOT
<td><img src="{\$info['{$fields}']}" height="50px" /></td>\n
EOT;
	        }elseif (isset($int_arr[0])&&(int)$int_arr[0]>=64) { //对长文本进行处理
	            $html_table_thead_th.=<<<EOT
<th class="hidden-480">{:lang('{$model_name}:{$fields}')}</th>\n
EOT;
	            $html_table_tr_td.=<<<EOT
<td class="hidden-480">{\$info['{$fields}']}</td>\n
EOT;
	        }else {
	           $html_table_thead_th.=<<<EOT
<th>{:lang('{$model_name}:{$fields}')}</th>\n
EOT;
	           $html_table_tr_td.=<<<EOT
<td>{\$info['{$fields}']}</td>\n
EOT;
	           //搜索框，只搜文本字段，其它的以后再考虑
	           $html_form_search.=<<<EOT
<label for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
<input type="text" name="{$fields}" value="{:input('{$fields}')}">\n
EOT;
	        }//end if	        
	    } //end foreach ($fields_config as $fields=>$type)
	    $search_arr=['{%html_form_search%}','{%html_table_thead_th%}','{%html_table_tr_td%}'];
	    $replace_arr=[$html_form_search,$html_table_thead_th,$html_table_tr_td];
	    $this->createHtml($demo_html_file,input('name'),$search_arr,$replace_arr);
	}
	/**
	 *  |替换添加的HTML
	 */
	protected function addReplace(){
	    //查出数据表对应的数据字段
	    $model_name=input('model_name');
	    $fields_config=model($model_name)->getFieldsConfig();
	    unset($fields_config['id']);
	    unset($fields_config['create_user_id']);
	    unset($fields_config['create_user_name']);
	    unset($fields_config['create_time']);
	    unset($fields_config['update_time']);
	    $html_form='';
	    //此操作必定要生成HTML
	    $demo_html_file=input('demo_html');
	    foreach ($fields_config as $fields=>$type){ //文本
	        if (stripos($type,'varchar')||stripos($type,'char')) {
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="text" class="form-control" id="{$fields}" name="{$fields}" value="">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }elseif (stripos($type,'int')||stripos($type,'tinyint')){  //数字
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="number" class="form-control" id="{$fields}" name="{$fields}" value="">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }elseif (stripos($type,'date')||stripos($type,'datetime')){  //日期和时间
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="text" class="form-control date-picker" id="{$fields}" name="{$fields}" value="">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }elseif (stripos($type,'text')){  //编辑器,如果是异步弹窗操作，需要考虑如何加载编辑器的JS
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<script id="container" name="{$fields}" type="text/plain"></script>
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }else{  //其它
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="text" class="form-control" id="{$fields}" name="{$fields}" value="">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }//end if
	    } //end foreach ($fields_config as $fields=>$type)
	    $this->createHtml($demo_html_file,input('name'),$search_arr,$replace_arr);
	}
	/**
	 * |替换修改的HTML
	 */
	protected function editReplace(){
	    //查出数据表对应的数据字段
	    $model_name=input('model_name');
	    $fields_config=model($model_name)->getFieldsConfig();
	    unset($fields_config['id']);
	    unset($fields_config['create_user_id']);
	    unset($fields_config['create_user_name']);
	    unset($fields_config['create_time']);
	    unset($fields_config['update_time']);
	    $html_form='';
	    //此操作必定要生成HTML
	    $demo_html_file=input('demo_html');
	    foreach ($fields_config as $fields=>$type){ //文本
	        if (stripos($type,'varchar')||stripos($type,'char')) {	            
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="text" class="form-control" id="{$fields}" name="{$fields}" value="{\$info['{$fields}']}">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }elseif (stripos($type,'int')||stripos($type,'tinyint')){  //数字
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="number" class="form-control" id="{$fields}" name="{$fields}" value="{\$info['{$fields}']}">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }elseif (stripos($type,'date')||stripos($type,'datetime')){  //日期和时间
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="text" class="form-control date-picker" id="{$fields}" name="{$fields}" value="{\$info['{$fields}']}">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }elseif (stripos($type,'text')){  //编辑器,如果是异步弹窗操作，需要考虑如何加载编辑器的JS
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<script id="container" name="{$fields}" type="text/plain">{\$info[{$fields}]|raw}</script>
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }else{  //其它
	            $html_form.=<<<EOT
<div class="form-group">
    <label class="col-sm-3 control-label" for="{$fields}">{:lang('{$model_name}:{$fields}')}：</label>
	<div class="col-sm-9">
		<input type="text" class="form-control" id="{$fields}" name="{$fields}" value="{\$info['{$fields}']}">
	</div>
</div>
EOT;
	            $search_arr=['{%html_form%}'];
	            $replace_arr=[$html_form];
	        }//end if
	    } //end foreach ($fields_config as $fields=>$type)
	    $this->createHtml($demo_html_file,input('name'),$search_arr,$replace_arr);
	}
	/**
	 * |创建HTML
	 * @param unknown $demo_html_file
	 * @param unknown $create_html
	 * @param unknown $search_arr
	 * @param unknown $replace_arr
	 * @return mixed
	 */
	protected function createHtml($demo_html_file,$create_html,$search_arr,$replace_arr){
	    $demo_html_file=file_get_contents('../application/pangu/view/'.$demo_html_file);
	   // echo $demo_html_file;exit;
	    $html=str_replace($search_arr,$replace_arr, $demo_html_file);
	    $html_path=explode('/', $create_html);	    
	    if (strpos($html_path[1], '.')) { //当时管理后台或者二级目录时
	        $admin_folder=explode('.',$html_path[1]);
	        $pathname="../application/{$html_path[0]}/view/{$admin_folder[0]}/";
	        if (!is_dir($pathname)) {
	            mkdir($pathname);
	        }
	        $controller_file=Loader::parseName($admin_folder[1]);
	        $html_path[2]=Loader::parseName($html_path[2]);
	        $html_path="../application/{$html_path[0]}/view/{$admin_folder[0]}/{$controller_file}-{$html_path[2]}.html";
	        file_put_contents($html_path, $html);
	    }else {
	        $html_path[1]=Loader::parseName($html_path[1]);
	        $html_path[2]=Loader::parseName($html_path[2]);
	        $html_path="../application/{$html_path[0]}/view/{$html_path[1]}-{$html_path[2]}.html";
	        file_put_contents($html_path, $html);
	    }	    
	}
	
}