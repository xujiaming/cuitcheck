<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * 管理端基类Controller
 */

class HomeBaseController extends BaseController{
    /**
     * 初始化方法
     */
    public function _initialize(){
        parent::_initialize();
        // 判断网站是否关闭
        if(C('WEB_STATUS')!=1){
        	//配置网站的暂停访问的界面
            $this->display('Public/web_close');
            exit();
        }
        //判断是否登录，没有登录就跳转到后台的登录界面
        if(!isset($_SESSION['account']) || empty($_SESSION['account'])){
            //重置到登录页面
            redirect(U('Home/Login/login'));
        }
    }
}

