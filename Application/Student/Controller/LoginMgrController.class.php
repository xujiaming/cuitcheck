<?php
namespace Student\Controller;
use Think\Controller;
/*
罗钞
2017.7.15
功能:学生端的登陆以及资料的显示
 **/
class LoginMgrController extends Controller {
    public function login(){
        $this->display();
    }
    public function testInform(){
    	$data=D('inform')->getInformList();
    	$this->assign('informlist',$data);
    	$this->display();
    }
    public function verify(){
        ob_clean();
         $Verify = new \Think\Verify();
        $Verify->fontSize = 30;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->useCurve =false;
        $Verify->entry();
    }
  public function checkverify($code){
        
        $verify = new \Think\Verify();  
        $result=  $verify->check($code, $id);
        if($result){
            $data['code']=1;
            $data['success'] = true;
        }else{
            $data['code']=2;
            $data['status'] = false;
            $data['msg']='验证码输入错误！';
        }
        return $data;
    }
    public function checklogin(){
           $temp=$_POST;
            // dump(md5(sha1($temp['password'])));die();
            $map['account']=array('eq',$temp['account']);
            $map['password']=array('eq',md5(sha1($temp['password'])));
            $map['del_flag']=array('eq',1);
            $passtrue=M('student')->where($map)->find();
            $passtrue2=M('student')->where(array('account'=>$temp['account'],'password'=>$temp['password']))->find();
            // dump($passtrue2);die();
            $data2=$this->checkverify($temp['code']);
            if($data2['code']==1){
                if((sizeof($passtrue)!=0)||(sizeof($passtrue2)!=0)){
                    if(($passtrue['status']!=0)||($passtrue2['status']!=0)){
                             $data=array('status'=>success);
                            session('stu_account',$temp['account']);
                     
                      }else{
                           $data=array('status'=>false,'msg'=>'该用户已被禁用!');  
                     }
                }else{
                    $data=array('status'=>false,'msg'=>'用户名或密码错误!');
                }
            }
            else{
                $data=$data2;
            }
            
         $this->ajaxReturn($data,'json');
    }
   
    public function loginout(){
        session(null);
    }
}