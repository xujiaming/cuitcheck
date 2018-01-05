<?php
namespace Student\Controller;
use Think\Controller;
use Common\Controller\StudentBaseController;
/*
罗钞
2017.7.16
功能:学生端资料的显示,以及相关操作
 **/
class IndexMgrController extends StudentBaseController {
    public function index(){
         $personData=$this->personInfo();       
        // dump($personData);die();
        $this->assign('person',$personData);
          $inform=$this->Inform();
        $this->assign('info',$inform);
        $this->display();
    }
    public function Inform(){
    	$data=D('inform')->getInformList();
    	// $this->assign('informlist',$data);
    	return $data;
    }
    public function personInfo(){
        $account=session('stu_account');
        // dump($account);die();
        $map['account']=array('eq',$account);
    	$data=M('Student')
        ->join('LEFT join kh_college ON kh_college.id = kh_student.dept_id
             LEFT join kh_major ON kh_major.id = kh_student.major_id 
             LEFT join kh_grade ON kh_grade.id = kh_student.grade_id 
             LEFT join kh_class ON kh_class.id = kh_student.class_id
            ')
        ->field('kh_student.id,account,kh_student.name,sex,kh_class.name as class_name,kh_grade.name as grade_name,kh_major.name as major_name,kh_college.name as college_name,photo,email,phone')
        ->where($map)
        ->find();
        // dump($data);die();
        return $data;
    }
    public function editPersonInfo(){
        // $temp=$_POST;
        // dump($_POST);die();
        if (!IS_POST) {
            $result=array('status'=>false,'msg'=>"提交方式不正确!");
        }else{
            $data['id']=I('studentid');
            $data['phone']=I('phone');
            $data['email']=I('email');
            $student=M('student');
            if(false!=$student->save($data)){
                $result=array('status'=>success);
            }else{
                $result=array('status'=>false,'msg'=>'保存失败!');
            }
        }
         $this->ajaxReturn($result,'json');
    }
    public function upimage(){
              $id=I('userid');
              // dump($id);die();
            $upload = new \Think\Upload();
            //设置上传文件大小，此处为 3M
            $upload->maxSize = 3145728;
            //上传文件类型
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            //设置时区为中国
            date_default_timezone_set('prc');
            $time = time();
            $tempName = $time."".rand(1,10000);
            //设置文件名保存为时间加随机数
            $upload->saveName = $tempName;
            //设置文件保存路径
            $upload->savePath = '/studentPhoto/';
            $info   =   $upload->uploadOne($_FILES['photo']);
            $result=array();
            // dump($info);die();
             if(!$info) {// 上传错误提示错误信息
                  $result['code']=1;
             }else{// 上传成功 获取上传文件信息
                $data['id']=$id;
                $data['photo']=$upload->rootPath.$info['savepath'].$info['savename']; 
                $User =M('student')->save($data);
                $result['code'] = 2;//设置code为2表示导入成功
            }
            $this->ajaxreturn($result,'json');
    }
    public function chengepsw(){
         if (!IS_POST) {
            $result=array('status'=>false,'msg'=>"提交方式不正确!");
        }else{
            $oldPassword=I('oldPassword');
            $newPassword=I('newPassword');
            $renewPassword=I('renewPassword');
            $id=I('studentid');
            $map['id']=array('eq',$id);
            $map['password']=array('eq',md5(sha1($oldPassword)));
            $istrue=M('student')->where($map)->find();
            if(sizeof($istrue)!=0){
                if ($newPassword==$renewPassword) {
                    $data['id']=$id;
                    $data['password']=md5(sha1($newPassword));
                    $student=M('student');
                    if(false!=$student->save($data)){
                           $result=array('status'=>success);
                     }else{
                        $result=array('status'=>false,'msg'=>'保存失败!');
                     }
                }else{
                    $result=array('status'=>false,'msg'=>'两次新密码不一致!');    
                }
            }else{
                $result=array('status'=>false,'msg'=>'原密码输入错误!');
            }   
        }
         $this->ajaxReturn($result,'json');
    }
}