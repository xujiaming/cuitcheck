<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：行政班级管理控制器
 * 作者：罗钞
 * 日期：2017/3/2
 */
class ClassMgrController extends HomeBaseController {

	public function classList(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$beginDate = I('beginDate', '');	//获取开始时间
		$endDate = I('endDate', '');		//获取结束时间
		$keyword = I('keyword', '');		//获取查询关键词
		$rows = 10;		//每页展示的数据
		$gradekey=I('gradekey','');

		$data = D('class')->getAllList($requestPage, $beginDate, $endDate, $keyword, $rows,$gradekey);
		$data_grade=D('courseclass')->getgradelist();

		$this->assign('gradelst',$data_grade);
		$this->assign('classlst', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('keyword', $keyword);
		$this->assign('gradekey', $gradekey);

		$this->display();
	}
	public function editClass(){
		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('class')->getClassId($id);
			$this->assign('class', $data);
		}
		$data_college=D('courseclass')->getcollgelist();
		$data_grade=D('courseclass')->getgradelist();

		$this->assign('collegelst',$data_college);
		$this->assign('gradelst',$data_grade);
		$this->assign('type', $type);

		$this->display('editclass');
	}
	/**
	 * 添加行政班级的处理
	 * 功能：行政班级添加方法
 	 * 作者：罗钞
 	 * 日期：2017/3/3
	 */
	public function add_class(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('class')->add_class();
		}

		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 添加行政班级的处理
	 * 功能：行政班级修改方法
 	 * 作者：罗钞
 	 * 日期：2017/3/3
	 */
	public function updateclass(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('class')->updateclass();
		}

		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 添加行政班级的处理
	 * 功能：行政班级删除方法
 	 * 作者：罗钞
 	 * 日期：2017/3/3
	 */
	public function deleteclass(){
		$id = I('id');

		$data = D('class')->daleteclass($id);

		$this->ajaxReturn($data, 'json');
	}

}
?>