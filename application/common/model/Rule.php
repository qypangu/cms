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
class Rule extends Model{    
    protected $table='pg_auth_rule';    // 设置当前模型对应的完整数据表名称
    public $model_table='auth_rule';
    protected static function init(){
        
    }
    /**
     * 读取树型菜单
     * @return mixed|string
     */
    public function getMenuTree(){
        if (input('menu_selected')) {
            cookie('menu_selected',input('menu_selected'));
        }        
        if (session('pg_admin_menu')) {
            return unserialize(session('pg_admin_menu'));
        }
        $tree_menu='';
        $icon[1]='fa-desktop';
        $icon[2]='fa-list';
        $icon[3]='fa-pencil-square-o';
        $icon[4]='fa-list-alt';
        $icon[5]='fa-picture-o';
        $icon[6]='fa-folder ';
        $icon[7]='fa-gavel ';
        $icon[8]='fa-cog ';
        $icon[9]='fa-leaf ';
        $icon[10]='fa-globe ';
        for($i=10;$i<100;$i++){
            $icon[$i]='fa-globe ';
        }
        $where['menu_type']='menu_left';
        $where['level']='1';
        $where['pid']=0;
        $where['status']=1;
        $list_rule=db('authRule')->where($where)->order('list_sort ASC')->select();
        $i=0;
        $auth=new \think\Auth();
        foreach ($list_rule as $rule){
            if (session('auth_user_id')!=1) {
                if (!$auth->check($rule['name'],session('auth_user_id'))) {
                    continue;
                }
            }
            $i++;
            $tree_menu[$i]['key']=$rule['name'];
            $tree_menu[$i]['icon']=$icon[$i];
            $tree_menu[$i]['title']=$rule['title'];
            $tree_menu[$i]['list_sort']=$rule['list_sort'];
            $tree_menu[$i]['url']='#';
            //读二级菜单
            $where['level']='2';
            $where['pid']=$rule['list_sort'];
            $list_rule_2=db('authRule')->where($where)->order('list_sort ASC')->select();
            $j=0;
            foreach($list_rule_2 as $rule_2){
                if (session('auth_user_id')!=1) {
                    if (!$auth->check($rule_2['name'],session('auth_user_id'))) {
                        continue;
                    }
                }
                $j++;
                $tree_menu[$i]['submenu'][$j]['key']=$rule_2['name'];
                $tree_menu[$i]['submenu'][$j]['icon']='fa-desktop';
                $tree_menu[$i]['submenu'][$j]['title']=$rule_2['title'];
                $tree_menu[$i]['submenu'][$j]['url']=url($rule_2['name'],['menu_selected'=>$rule_2['list_sort']]);
                $tree_menu[$i]['submenu'][$j]['list_sort']=$rule_2['list_sort'];
                $tree_menu[$i]['submenu'][$j]['submenu']='';
            }
        }
        session('pg_admin_menu',serialize($tree_menu));
        return $tree_menu;
    }
}
