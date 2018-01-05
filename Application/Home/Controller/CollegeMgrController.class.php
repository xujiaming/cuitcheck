<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：学院管理
 * 作者：maqingwen
 * 日期： 2017/2/24
 */
class CollegeMgrController extends HomeBaseController{

	/**
	 * 功能：学院列表展示界面
	 * @param requestPage, beginDate, endDate, keyword
	 * @return collegeList
	 */
	public function collegeList(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$beginDate = I('beginDate', '');	//获取开始时间
		$endDate = I('endDate', '');		//获取结束时间
		$keyword = I('keyword', '');		//获取查询关键词
		$rows = 10;		//每页展示的数据

		$data = D('College')->getAllList($requestPage, $beginDate, $endDate, $keyword, $rows);
		$this->assign('collegeList', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('keyword', $keyword);

		$this->display('collegeList');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param type, id
	 * @return type, college
	 */
	public function editCollege(){

		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data  = D('College')->getCollegeById($id);
			$this->assign('college', $data);
		}
		$this->assign('type', $type);

		$this->display('editCollege');
	}

	/**
	 * 修改信息的事件处理
	 * @param college
	 * @return 
	 */
	public function updateCollege(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('College')->updateCollege();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 添加信息的事件处理
	 * @param college
	 * @return
	 */
	public function addCollege(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('College')->addCollege();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 删除信息的事件处理
	 * @param id
	 * @return
	 */
	public function deleteCollege(){
		$id = I('id');

		$data = D('College')->daleteCollege($id);

		$this->ajaxReturn($data, 'json');
	}
}