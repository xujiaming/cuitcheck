<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * 学生端的基类Controller 
 */
class StudentBaseController extends BaseController{
    /**
     * 初始化方法
     */
    public function _initialize(){
        parent::_initialize();
        //下面写限定未登录不能访问其余模块的方法 
         if(C('WEB_STATUS')!=1){
        	//配置网站的暂停访问的界面
            $this->display('Public/web_close');
            exit();
        }
        //判断是否登录，没有登录就跳转到后台的登录界面
        if(!isset($_SESSION['stu_account']) || empty($_SESSION['stu_account'])){
            //重置到登录页面
            redirect(U('Student/LoginMgr/login'));
        }
    }


}

