<?php
namespace app\common\controller;

class WebAuth extends Pg{
    protected $model_name;
    protected function initialize(){
        if (!session('?auth_user_id')) {
            $this->redirect('Publics/login');exit;
        }
        $this->init();
    }
    protected function init(){
        
    }
}
