<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：系统管理控制器
 * 作者：taolei
 * 日期：2017/2/17
 */
class SystemController extends HomeBaseController {

	public function index(){
	    $this->display();
	}

	/**
	 * 日志管理的模板渲染
	 * @Author   taolei
	 * @DateTime 2017-02-20
	 * @return   null     null
	 */
	public function log(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$beginDate = I('beginDate', '');	//获取发布时间
		$endDate = I('endDate', '');		//获取最大时间
		$rows = C('PAGE_SIZE');				//每页展示的数据
		$data=D('Log')->getLogList($requestPage,$endDate,$beginDate,$rows);
		$this->assign('pages',$data['pages']);
		$this->assign('logList',$data['list']);
		$this->assign('requestPage',$requestPage);
		$this->assign('beginDate',$beginDate);
		$this->assign('endDate',$endDate);
	    $this->display();
	}
	/**
	 * 菜单管理的模板渲染
	 * @Author   taolei
	 * @DateTime 2017-02-20
	 * @return   null     null
	 */
	public function menu(){
		$data = D('Menu')->getMenuList();
		$this->assign('menuList',$data);
	    $this->display();
	}

	/**
	 * 菜单删除
	 * @Author   taolei
	 * @return   json
	 */
	public function deleteMenu(){
	    $type = I('type');
	    $id = I('id');
	    $flag = false;
	    if($type!='' && $id!=''){
	    	$flag = D('Menu')->deleteNode($type,$id);
	    }
	    $data = array();
	    if($flag){
	    	$data = array('status'=>0, 'msg'=>'操作成功');
	    }else{
	    	$data = array('status'=>1, 'msg'=>'操作失败请重试');
	    }
	    $this->ajaxReturn($data,'json');
	}

	/**
	 * 菜单节点的修改
	 * @Author   taolei
	 * @return   json
	 */
	public function editMenu(){
		$edit = $_POST;
		$id = $edit['id'];
		if($id != ''){
			$flag = D('Menu')->updateNode($edit);
		}else{
			$flag = D('Menu')->addNode($edit);
		}
		if($flag){
	    	$data = array('status'=>0, 'msg'=>'操作成功');
	    }else{
	    	$data = array('status'=>1, 'msg'=>'操作失败!');
	    }
	    $this->ajaxreturn($data,'json');
	}

	//节点的添加
	public function addMenu(){
	    $new = $_POST;
	    if($new['parent_id']!=''){
	    //添加子节点
	    	$new['tree_code'] = 2;
	    }else{
	    	$new['parent_id'] = 0;
	    	$new['tree_code'] = 1;
	    }
	    $flag = D('Menu')->addNode($new);
	    if($flag){
	    	$data = array('status'=>0, 'msg'=>'操作成功');
	    }else{
	    	$data = array('status'=>1, 'msg'=>'操作失败!');
	    }
	    $this->ajaxreturn($data,'json');
	}

	//节点添加界面渲染
	public function addView(){
		$type = $_GET['type'];
		$fid = '';
		$fname='';
		if($type=='son'){
		   $fid = $_GET['fid'];
		   $fname = $_GET['fname'];
		}
		$this->assign('fid',$fid);
		$this->assign('fname',$fname);
	    $this->display();
	}
}
?>