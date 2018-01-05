<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：年级管理
 * @author maqingwen
 * 日期：2017/2/27
 */
class GradeMgrController extends HomeBaseController{

	/**
	 * 功能：年级列表展示
	 * @param requestPage, beginDate, endDate, keyword
	 * @return gradeList
	 */
	public function gradeList(){

		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$beginDate = I('beginDate', '');	//获取开始时间
		$endDate = I('endDate', '');		//获取结束时间
		$keyword = I('keyword', '');		//获取查询关键词
		$rows = 10;		//每页展示的数据

		$data = D('Grade')->getAllList($requestPage, $beginDate, $endDate, $keyword, $rows);
		$this->assign('gradeList', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('keyword', $keyword);

		$this->display('gradeList');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param type, id
	 * @return type, grade
	 */
	public function editGrade(){

		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('Grade')->getGradeById($id);
			$this->assign('grade', $data);
		}
		$this->assign('type', $type);

		$this->display('editGrade');
	}


	/**
	 * 修改信息的事件处理
	 * @param college
	 * @return 
	 */
	public function updateGrade(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Grade')->updateGrade();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 添加信息的事件处理
	 * @param college
	 * @return
	 */
	public function addGrade(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Grade')->addGrade();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 删除信息的事件处理
	 * @param id
	 * @return
	 */
	public function deleteGrade(){
		$id = I('id');

		$data = D('Grade')->daleteGrade($id);

		$this->ajaxReturn($data, 'json');
	}


}