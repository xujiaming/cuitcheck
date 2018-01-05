<?php
namespace Home\Controller;
use Common\Controller\BaseController;

/**
 * 功能：管理员端的后台页面登录控制器
 * 作者：taolei
 * 日期：2017/2/5
 */
class LoginController extends BaseController {
    /**
     * 登录界面的模板渲染
     * @Author   taolei
     * @DateTime 2017-02-17
     * @return   null     null
     */
    public function login(){
        $this->display();
    }

 	/**
 	 * 验证码生成类
 	 * @Author   taolei
 	 * @DateTime 2017-02-17
 	 * @return   string     验证码
 	 */
    public function verify_c(){
        ob_clean();
        $Verify = new \Think\Verify();
    	$Verify->fontSize = 18;
    	$Verify->length   = 4;
    	$Verify->useNoise = false;
    	$Verify->codeSet = '0123456789';
    	$Verify->imageW = 125;
    	$Verify->imageH = 38;
    	//$Verify->expire = 600;
    	$Verify->entry();
    }
    /**
     * 验证码的验证类
     * @Author   taolei
     * @DateTime 2017-02-17
     * @param    String     $code 验证码
     * @param    string     $id   [description]
     * @return   验证结果         
     */
    public function check_verifycode(){
    	$code = I('code');
    	$verify = new \Think\Verify();  
    	$result=  $verify->check($code, $id);
    	if($result){
    		$data['success'] = true;
    	}else{
    		$data['success'] = false;
    	}
    	$this->ajaxReturn($data,'json');
    	
    }

    /**
     * 验证登录
     * @Author   taolei
     * @DateTime 2017-02-17
     * @return   json     返回结果json数组
     */
    public function check_login(){
        if(!IS_POST){
            $data = array('success'=>false,'msg'=>'提交方式不正确');
        }else{
            $temp = $_POST;
            $isset = M('Sysuser')->where(array('account'=>$temp['account'],'password'=>md5(sha1($temp['password']))))->select();
            if(sizeof($isset)==1){
                $data = array('success'=>true,'msg'=>'验证通过');
                session('account',$temp['account']);
                session('accInfo',$isset[0]);
                addlog(__ACTION__);
            }else{
                $data = array('success'=>false,'msg'=>'账号或密码不存在');
            }
        }
        $this->ajaxReturn($data,'json');
    }

    /**
     * 注销登录
     * @Author   taolei
     * @DateTime 2017-02-17
     * @return   null     null
     */
    public function Login_logout(){
        session(null);
        $this->redirect("/Home/Index/index");
    }
}