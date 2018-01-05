<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：权限管理控制器
 * 作者：罗钞
 * 日期：2017/7/21
 */
class SysuserRuleController extends HomeBaseController {

	public function sysRuleList(){
        $keyword = I('keyword', '');        //获取查询关键词
        $rows = 10;                 
        $data = D('authRule')->getAllList($requestPage,$keyword, $rows);
        // dump($data['pages']);die();

        $this->assign('rulelst', $data['list']);
        $this->assign('requestPage', $requestPage);
        $this->assign('keyword', $keyword);

	    $this->display();
	}
	public function editSysRule(){

        $type=I('type');
        // dump($type);die();
        $id = I('id');
        if ($type=='updaterule') {
            $map['id']=array('eq',$id);
            $data=M('Auth_rule')->where($map)->find();
            $this->assign('ruleedit',$data);
        }elseif ($type=='sonadd') {
            $this->assign('ruleid',$id);
        }
        // dump($type);die();
        $this->assign('type',$type);
        $this->display();

    }

    public function addSysRule(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            // dump($_POST);die();
            $data=D('authRule')->addrule();
        }

        $this->ajaxReturn($data, 'json');
    }
    public function addsonRule(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
        
            $_POST['pid']=$_POST['ruleid'];
            $data=D('authRule')->addsonrule();
        }

        $this->ajaxReturn($data, 'json');

    }

    public function updateSysRule(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $data = D('authRule')->updaterule();
        }

        $this->ajaxReturn($data, 'json');
    }
    public function deleteSysRule(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $id=I('id');
            // dump($id);die();
            $data = D('authRule')->deleterule($id);
        }

        $this->ajaxReturn($data, 'json');
    }

}
?>